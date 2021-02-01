<?php

namespace App\Controller;

use App\Entity\AboutMePage;
use App\Entity\Article;
use App\Entity\ContactPage;
use App\Entity\Image;
use App\Entity\Navbar;
use App\Entity\Page;
use App\Entity\PortofolioPage;
use App\Form\AboutMePageType;
use App\Form\ContactPageType;
use App\Form\NavbarType;
use App\Form\PageFormType;
use App\Form\PortofolioPageType;
use App\Service\FileUploader;
use App\Transformer\ImageToPageTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
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
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/baseAdmin.html.twig');
    }

    /**
     * @Route("/admin/addPage", name="add_page")
     */
    public function addPage(Request $request)
    {
        $form = $this->createForm(PageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
            * @var Page
            */
            $page = $form->getData();

            $this->em->persist($page);
            $this->em->flush();

            return $this->redirectToRoute('list_page');
        }

        return $this->render('admin/pages/addPage.html.twig', [
            'pageForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/editPage/{id}", name="edit_page")
     */
    public function editPage(Request $request, Page $page)
    {
        $form = $this->createForm(PageFormType::class, $page);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
          $page = $form->getData();
          $this->em->persist($page);
          $this->em->flush();

          return $this->redirectToRoute('list_page');
        }

        return $this->render('admin/pages/addPage.html.twig', [
            'pageForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delPage{id}", name="del_page")
     */
    public function deletePage(Page $page)
    {
        $this->em->remove($page);
        $this->em->flush();

        return $this->redirectToRoute('list_page');
    }

    /**
     * @Route("/admin/listPage", name="list_page")
     */
    public function listPage()
    {
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();

        return $this->render('admin/Articles/listArticles.html.twig', [
            'pages' => $pages
        ]);
    }

    public function addArticle(){

    }

    public function editArticle(){

    }

    public function deleteArticle(){

    }

    /**
     * @Route("/admin/listArticle", name="list_article")
     */
    public function listArticle()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('admin/Articles/listArticles.html.twig', [
                'articles' => $articles
        ]);

    }

    /**
     * @Route("/admin/portofolio_manager", name="portofolio_manager")
     */
    public function portofolio(Request $request, EntityManagerInterface $em)
    {
        $portofolio = $em->getRepository(PortofolioPage::class)->findOneBy(['slug'=>'portofolioPage']);

        $form = $this->createForm(PortofolioPageType::class, $portofolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //in case someday add sometext
            //$portofolio = $form->getData();
            //$natureFiles = $request->files->get('portofolio_page')['natureGallery'];
            $natureFiles = $form['natureGallery']->getData();
            if (!empty($natureFiles)) {
                $portofolio->removeAllNatureGallery();
                $natureGalleryRoute = $this->getParameter('upload_directory').'natureGallery/';
                $fileUploader = new FileUploader($natureGalleryRoute);
                $fileUploader->removeAll();

                foreach ($natureFiles as $file){
                    $filename = $fileUploader->uploadImage($file);

                    $image = new Image();
                    $image->setName($filename);
                    $image->setUrl($this->getParameter('upload_directory').'natureGallery/'.$filename);

                    $portofolio->addNatureGallery($image);
                }
            }

            $eventfiles = $form['eventGallery']->getData();
            if (!empty($eventfiles)) {
                $portofolio->removeAlleventGallery();
                $eventGalleryRoute = $this->getParameter('upload_directory').'eventGallery/';
                $fileUploader = new FileUploader($eventGalleryRoute);
                $fileUploader->removeAll();

                foreach ($eventfiles as $file){
                    $filename = $fileUploader->uploadImage($file);

                    $image = new Image();
                    $image->setName($filename);
                    $image->setUrl($this->getParameter('upload_directory').'eventGallery/'.$filename);

                    $portofolio->addEventGallery($image);
                }
            }

            $sesionFiles = $form['sesionGallery']->getData();
            if (!empty($sesionFiles)) {
                $portofolio->removeAllSesionGallery();
                $sesionGalleryRoute = $this->getParameter('upload_directory').'sesionGallery/';
                $fileUploader = new FileUploader($sesionGalleryRoute);
                $fileUploader->removeAll();

                foreach ($sesionFiles as $file){
                    $filename = $fileUploader->uploadImage($file);

                    $image = new Image();
                    $image->setName($filename);
                    $image->setUrl($this->getParameter('upload_directory').'sesionGallery/'.$filename);

                    $portofolio->addSesionGallery($image);
                }
            }

            $showfiles = $form['showGallery']->getData();
            if (!empty($showfiles)) {
                $portofolio->removeAllShowGallery();
                $showGalleryRoute = $this->getParameter('upload_directory').'showGallery/';
                $fileUploader = new FileUploader($showGalleryRoute);
                $fileUploader->removeAll();

                foreach ($showfiles as $file){
                    $filename = $fileUploader->uploadImage($file);

                    $image = new Image();
                    $image->setName($filename);
                    $image->setUrl($this->getParameter('upload_directory').'showGallery/'.$filename);

                    $portofolio->addShowGallery($image);
                }
            }

            $em->persist($portofolio);
            $em->flush();

            return $this->redirectToRoute('portofolio_manager');
        }
        return $this->render('admin/portofolio.html.twig', [
            'portofolioForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/contact_manager", name="contact_manager")
     */
    public function contact(Request $request, EntityManagerInterface $em){
        $contactPage = $em->getRepository(ContactPage::class)->findOneBy(['slug'=>'contactPage']);

        $form = $this->createForm(ContactPageType::class, $contactPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var ContactPage
             */
            $contactPage = $form->getData();

            $em->persist($contactPage);
            $em->flush();

            return $this->redirectToRoute('contact_manager');
        }
        return $this->render('admin/contact.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/about_me_manager", name="about_me_manager")
     */
    public function aboutMe(Request $request, EntityManagerInterface $em){
        $aboutMePage = $em->getRepository(AboutMePage::class)->findOneBy(['slug'=> 'aboutMePage']);

        $form = $this->createForm(AboutMePageType::class, $aboutMePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var AboutMePage
             */
            $aboutMePage = $form->getData();

            $em->persist($aboutMePage);
            $em->flush();

            return $this->redirectToRoute('about_me_manager');
        }
        return $this->render('admin/aboutMe.html.twig', [
            'aboutMeForm' => $form->createView()
        ]);
    }
}
