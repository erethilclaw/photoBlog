<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Navbar;
use App\Entity\Page;
use App\Form\PageFormType;
use Symfony\Component\HttpFoundation\Request;


class PageController extends AbstractController
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
     * @Route("/admin/addPage", name="add_page", methods={"POST"} )
     */
    public function addPage(Request $request)
    {
        $navbar = $this->getDoctrine()->getRepository(Navbar::class)->findOneBy(['slug'=>'front_header']);
        $page = new Page;
        $slug = $request->get('page_slug');
        $page->setSlug($slug);
        $page->setNavbar($navbar);
        $page->setTitleCa($page->getSlug().'-ca');
        $page->setTitleEs($page->getSlug().'-es');
        $page->setTitleEn($page->getSlug().'-en');

        $this->em->persist($page);
        $this->em->flush();

        return $this->redirectToRoute('list_page');
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

        return $this->render('admin/pages/listPages.html.twig', [
            'pages' => $pages
        ]);
    }
}
