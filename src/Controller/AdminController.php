<?php

namespace App\Controller;

use App\Entity\PortofolioPage;
use App\Form\PortofolioPageType;
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

        $form = $this->createForm(PortofolioPageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dd($form->getData());
            /**
             * @var PortofolioPage $page
             */
            $page = $form->getData();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('llistatMarques');
        }
        return $this->render('admin/portofolio.html.twig', [
            'portofolioForm' => $form->createView()
        ]);
    }
}
