<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\PortofolioPage;
use App\Form\PortofolioPageType;
use App\Transformer\ImageToPageTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $uploads;

    public function __construct(string $uploads)
    {
        $this->uploads = $uploads;
    }

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

        $form = $this->createForm(PortofolioPageType::class, $page[0]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->get('portofolio_page')['natureGallery'];
            /**
             * @var PortofolioPage $page
             */
            $page = $form->getData();

            foreach ($files as $file){
                $filename = md5(uniqid()).'.'. $file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $filename);

                $image = new Image();
                $image->setName($filename);
                $image->setUrl($this->getParameter('upload_directory').$filename);

                $page->addNatureGallery($image);
            }

            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('portofolio_manager');
        }
        return $this->render('admin/portofolio.html.twig', [
            'portofolioForm' => $form->createView()
        ]);
    }
}
