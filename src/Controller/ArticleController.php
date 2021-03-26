<?php

namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleFromType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/admin/addArticle", name="add_article")
     */
    // public function addArticle(Request $request, UploaderHelper $uploaderHelper){
    //     $form = $this->createForm(ArticleFromType::class);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         /**
    //          * @var Article $article
    //          */
    //         $article = $form->getData();

    //         /**
    //          * @var UploadedFile $uploadedFile
    //          */
    //         $uploadedFile = $form['imageFile']->getData();

    //         if ($uploadedFile){
    //             $newFileName = $uploaderHelper->uploadArticleImage($uploadedFile, $article->getImageFileName());
    //             $article->setImageFileName($newFileName);
    //         }

    //         $this->em->persist($article);
    //         $this->em->flush();

    //         return $this->redirectToRoute('list_article');
    //     }

    //     return $this->render('admin/articles/addArticle.html.twig', [
    //         'articleForm' => $form->createView(),
    //     ]);
    // }

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

            return $this->redirectToRoute('list_article');
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
   
}
