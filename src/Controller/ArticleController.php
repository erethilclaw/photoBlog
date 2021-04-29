<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Page;
use App\Form\ArticleFromType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{
    private $em;

    /**
     * AdminController constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/page/{id}/article", name="admin_page_add_article", methods={"POST"})
     */
    public function addArticleToPage(Page $page, Request $request)
    {
        $article = new Article();
        $slug = json_decode($request->getContent(), true);
        $article->setSlug($slug);
        $article->setTitleEn($article->getSlug().'_en');
        $article->setTitleCa($article->getSlug().'_ca');
        $article->setTitleEs($article->getSlug().'_es');

        $page->addArticle($article);

        $this->em->persist($article);
        $this->em->flush();

        return $this->json(
            $article,
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/page/{id}/article", methods="GET", name="admin_page_list_articles")
     */
    public function getPageArticles(Page $page)
    {
        return $this->json(
            $page->getArticles(),
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/editArticle/{id}", name="edit_article")
     */
    public function editArticle(Request $request, Article $article, UploaderHelper $uploaderHelper){
        $form = $this->createForm(ArticleFromType::class, $article);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            /**
             * @var UploadedFile $uploadedFile
             */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile){
                $newFileName = $uploaderHelper->uploadArticleImage($uploadedFile, $article->getImageFilename());
                $article->setImageFileName($newFileName);
            }

            $this->em->persist($article);
            $this->em->flush();

            $pageId = $article->getPage()->getId();

            return $this->redirectToRoute('edit_page', ['id' => $pageId]);
        }

        return $this->render('admin/articles/editArticle.html.twig', [
            'articleForm' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * @Route("/admin/delArticle{id}", name="del_article")
     */
    public function deleteArticle(Article $article){
        $this->em->remove($article);
        $this->em->flush();

        return $this->redirectToRoute('list_article');
    }

    /**
     * @Route("/admin/listArticle", name="list_article")
     */
    public function listArticle()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('admin/articles/listArticles.html.twig', [
                'articles' => $articles
        ]);

    }

    /**
     * @Route("/admin/page/{id}/article/reorder", methods="POST", name="admin_page_reorder_articles")
     */
    public function reorderArticleReferences(Page $page, Request $request)
    {
        $orderedIds = json_decode($request->getContent(), true);

        if ($orderedIds === false) {
            return $this->json(['detail'=> 'invalid body'], 400);
        }

        $orderedIds = array_flip($orderedIds);

        foreach ($page->getArticles() as $article) {
            $article->setPosition($orderedIds[$article->getId()]); 
        }

        $this->em->flush();

        return $this->json(
            $page->getArticles(),
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/page/article/{id}", name="admin_page_update_article", methods={"PUT"})
     */
    public function updateArticleReference(Article $article, SerializerInterface $serialazer, Request $request, ValidatorInterface $validator)
    {
        $serialazer->deserialize(
            $request->getContent(),
            Article::class,
            'json',
            [
                'object_to_populate' => $article,
                'groups' => ['main']
            ]
        );

        $violations = $validator->validate($article);
        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $this->em->persist($article);
        $this->em->flush();

        return $this->json(
            $article,
            200,
            [],
            [
                'groups' => ['main']
            ]
        );
    }
}
