<?php

namespace App\Controller;

use App\Entity\PortofolioPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     */
    public function index(EntityManagerInterface $em)
    {
        $page = $em->getRepository(PortofolioPage::class)->findOneBy(['slug'=>'portofolioPage']);

        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
            'portofolioPage' => $page
        ]);
    }
}
