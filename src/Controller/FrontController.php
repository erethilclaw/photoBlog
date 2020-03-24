<?php

namespace App\Controller;

use App\Entity\PortofolioPage;
use App\Form\MailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="front")
     */
    public function index()
    {
        $portofofiloPage = $this->em->getRepository(PortofolioPage::class)->findOneBy(['slug'=>'portofolioPage']);

        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
            'portofolioPage' => $portofofiloPage
        ]);
    }

    /**
     * @Route("/contact", name="contact_page")
     */
    public function contact(Request $request)
    {
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);
    }
}
