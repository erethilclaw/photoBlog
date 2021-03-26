<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleReference;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;

class ArticleReferenceAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/{id}/references", name="admin_article_add_reference", methods={"POST"})
     */
    public function uploadArticleReference(Article $article, Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('reference');

        if ($uploadedFile) {
            $filename = $uploaderHelper->uploadArticleReference($uploadedFile);

            $articleReference = new ArticleReference($article);
            $articleReference->setFilename($filename);
            $articleReference->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
            $articleReference->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');

            $violations = $validator->validate(
                $uploadedFile,
                [
                    new NotBlank(),
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/*'
                        ]
                    ])
                ]

            );

            if ($violations->count() > 0) {
                return $this->json($violations, 400);
            }

            $em->persist($articleReference);
            $em->flush();
        }

        return $this->json(
            $articleReference,
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/editArticle/{id}/references", methods="GET", name="admin_article_list_references")
     */
    public function getArticleReferences(Article $article)
    {
        return $this->json(
            $article->getArticleReferences(),
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/editArticle/{id}/references/reorder", methods="POST", name="admin_article_reorder_references")
     */
    public function reorderArticleReferences(Article $article, Request $request, EntityManagerInterface $em)
    {
        $orderedIds = json_decode($request->getContent(), true);

        if ($orderedIds === false) {
            return $this->json(['detail'=> 'invalid body'], 400);
        }

        $orderedIds = array_flip($orderedIds);

        foreach ($article->getArticleReferences() as $reference) {
            $reference->setPosition($orderedIds[$reference->getId()]); 
        }

        $em->flush();

        return $this->json(
            $article->getArticleReferences(),
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/article/references/{id}/download", name="admin_article_download_reference", methods={"GET"})
     */
    public function downloadArticleReference(ArticleReference $reference, UploaderHelper $uploaderHelper)
    {
        $article = $reference->getArticle();
        //$this->denyAccessUnlessGranted('ROLE_ADMIN', $article);

        $response = new StreamedResponse(function () use ($reference, $uploaderHelper) {
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $uploaderHelper->readStream($reference->getFilePath(), false);

            stream_copy_to_stream($fileStream, $outputStream);
        });

        $response->headers->set('Content-Type', $reference->getMimeType());

        return $response;
    }

    /**
     * @Route("/admin/editArticle/references/{id}", name="admin_article_delete_reference", methods={"DELETE"})
     */
    public function deleteArticleReference(ArticleReference $reference, UploaderHelper $uploaderHelper, EntityManagerInterface $em)
    {
    
        $article = $reference->getArticle();
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $article);

        $em->remove($reference);
        $em->flush();

        $uploaderHelper->deleteFile($reference->getFilePath(), false);

        return new Response(null, 204);
    }

    /**
     * @Route("/admin/editArticle/references/{id}", name="admin_article_update_reference", methods={"PUT"})
     */
    public function updateArticleReference(ArticleReference $reference, UploaderHelper $uploaderHelper, EntityManagerInterface $em, SerializerInterface $serialazer, Request $request, ValidatorInterface $validator)
    {
        $article = $reference->getArticle();
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $article);

        $serialazer->deserialize(
            $request->getContent(),
            ArticleReference::class,
            'json',
            [
                'object_to_populate' => $reference,
                'groups' => ['input']
            ]
        );

        $violations = $validator->validate($reference);
        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $em->persist($reference);
        $em->flush();

        return $this->json(
            $reference,
            200,
            [],
            [
                'groups' => ['main']
            ]
        );
    }
}
