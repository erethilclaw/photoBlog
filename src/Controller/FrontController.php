<?php

namespace App\Controller;

use App\Entity\AboutMePage;
use App\Entity\Contact;
use App\Entity\ContactPage;
use App\Entity\Navbar;
use App\Entity\PortofolioPage;
use App\Form\ContactType;
use App\Mailer\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


class FrontController extends AbstractController
{
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $em, MailerService $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="front")
     */
    public function index()
    {
        $navbar = $this->em->getRepository(Navbar::class)->findOneBy(['slug'=>'front_header']);
        $portofofiloPage = $this->em->getRepository(PortofolioPage::class)->findOneBy(['slug'=>'portofolioPage']);

        return $this->render('front/index.html.twig', [
            'navbar' => $navbar,
            'portofolioPage' => $portofofiloPage
        ]);
    }

    public function getPage()
    {

    }

    /**
     * @Route("/contact", name="contact_page")
     */
    public function contact(Request $request, TranslatorInterface $translator)
    {
        $contactPage = $this->em->getRepository(ContactPage::class)->findOneBy(['slug'=>'contactPage']);

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        /**
         * @var Contact
         */
        $mail = $form->getData();

        $this->em->persist($mail);
        $this->em->flush();

        $this->mailer->respondContact($mail);
        $this->mailer->notifyContact($mail);

        $this->addFlash('success', $translator->trans('flash_messages.mail_sended'));

        return $this->redirectToRoute('front');
    }

    return $this->render('front/contact.html.twig', [
        'mailForm' => $form->createView(),
        'contactPage' => $contactPage
    ]);

    }

    /**
     * @Route("/about_me", name="aboutMe")
     */
    public function aboutMe()
    {
        $aboutMePage = $this->em->getRepository(AboutMePage::class)->findOneBy(['slug'=>'aboutMePage']);


        return $this->render('front/aboutMe.html.twig', [
           'aboutMePage' => $aboutMePage
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        return $this->render('test.html.twig');
    }
}
