<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\PortofolioPage;
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
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/baseAdmin.html.twig');
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
            $natureFiles = $request->files->get('portofolio_page')['natureGallery'];
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

            $eventfiles = $request->files->get('portofolio_page')['eventGallery'];
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

            $sesionFiles = $request->files->get('portofolio_page')['sesionGallery'];
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

            $showfiles = $request->files->get('portofolio_page')['showGallery'];
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
}
