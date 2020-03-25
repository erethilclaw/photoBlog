<?php

namespace App\Controller;

use App\Entity\Mail;
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
        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        /**
         * @var Mail
         */
        $mail = $form->getData();

        $this->em->persist($mail);
        $this->em->flush();

        return $this->redirectToRoute('front');
    }

    return $this->render('front/contact.html.twig', [
       'mailForm' => $form->createView()
    ]);

    }
}
