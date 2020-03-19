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
        $page = $em->getRepository(PortofolioPage::class)->findAll();
        $portofolio = $page[0];

        $form = $this->createForm(PortofolioPageType::class, $portofolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = $request->files->get('portofolio_page')['natureGallery'];
            if (!empty($files)) {
                $portofolio->removeAllNatureGallery();
                $natureGalleryRoute = $this->getParameter('upload_directory').'natureGallery/';
                $fileUploader = new FileUploader($natureGalleryRoute);
                $fileUploader->removeAll();

                $portofolio = $form->getData();;

                foreach ($files as $file){
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

                $portofolio = $form->getData();;

                foreach ($eventfiles as $file){
                    $filename = $fileUploader->uploadImage($file);

                    $image = new Image();
                    $image->setName($filename);
                    $image->setUrl($this->getParameter('upload_directory').'eventGallery/'.$filename);

                    $portofolio->addEventGallery($image);
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
