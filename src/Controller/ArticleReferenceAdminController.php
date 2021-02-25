<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleReference;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
            //$articleReference->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');

            $violations = $validator->validate(
                $uploadedFile,
                [
                    new NotBlank(),
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/*',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'text/plain'
                        ]
                    ])
                ]

            );

            if ($violations->count() > 0) {
                /** @var ConstraintViolation $violation */
                $violation = $violations[0];
                $this->addFlash('error', $violation->getMessage());

                return $this->redirectToRoute('edit_article', [
                    'id' => $article->getId(),
                ]);
            }

            $em->persist($articleReference);
            $em->flush();
        }

        return $this->redirectToRoute('edit_article', [
            'id' => $article->getId(),
        ]);
    }
}