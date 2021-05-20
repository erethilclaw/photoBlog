<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Navbar;
use App\Entity\Page;
use App\Form\ContactType;
use App\Mailer\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


class FrontController extends AbstractController
{
    private $navbar;
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $em, MailerService $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->navbar = $this->em->getRepository(Navbar::class)->findOneBy(['slug'=>'front_header']);
    }

    /**
     * @Route("/", name="front")
     */
    public function index()
    {
        $homePage = $this->em->getRepository(Page::class)->findOneBy(['slug'=>'home']);

        return $this->render('front/index.html.twig', [
            'navbar' => $this->navbar,
            'homePage' => $homePage
        ]);
    }

    /**
     * @Route ("/home", name="home")
     */
    public function home()
    {
        $homePage = $this->em->getRepository(Page::class)->findOneBy(['slug'=>'home']);
        return $this->render('front/home.html.twig', [
            'navbar' => $this->navbar,
            'homePage' => $homePage
        ]);
    }

    /**
     * @Route ("/portofolio/{slug}", name="portofolio")
     */
    public function portofolio(Article $article)
    {
        return $this->render('front/portofolio.html.twig', [
            'navbar' => $this->navbar,
            'article' => $article
        ]);
    }

    /**
     * @Route ("/about_me", name="about_me")
     */
    public function about_me()
    {
        $aboutMePage = $this->em->getRepository(Page::class)->findOneBy(['slug'=>'about_me']);

        return $this->render('front/aboutMe.html.twig', [
            'navbar' => $this->navbar,
            'aboutMePage' => $aboutMePage
        ]);
    }

    /**
     * @Route ("/contact", name="contact")
     */
    public function contact(Request $request, TranslatorInterface $translator)
    {
        $contactPage = $this->em->getRepository(Page::class)->findOneBy(['slug'=>'contact']);
        
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
        'navbar' => $this->navbar,
        'mailForm' => $form->createView(),
        'contactPage' => $contactPage
    ]);
    }

    /**
     * @Route ("/page/{slug}", name="page")
     */
    public function page(Page $page)
    {
        return $this->render('front/default.html.twig', [
            'navbar' => $this->navbar,
            'page' => $page
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
