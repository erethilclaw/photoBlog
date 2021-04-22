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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;


class PageController extends AbstractController
{
    private $em;
    private $navbar;

    /**
     * AdminController constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->navbar = $this->em->getRepository(Navbar::class)->findOneBy(['slug' => 'front_header']);
    }

    /**
     * @Route("/admin/addPage", name="add_page", methods={"POST"} )
     */
    public function addPage(Request $request)
    {
        $page = new Page;
        $slug = $request->get('page_slug');
        $page->setSlug($slug);
        $page->setNavbar($this->navbar);
        $page->setTitleCa($page->getSlug() . '-ca');
        $page->setTitleEs($page->getSlug() . '-es');
        $page->setTitleEn($page->getSlug() . '-en');

        $this->em->persist($page);
        $this->em->flush();

        return $this->redirectToRoute('list_page');
    }

    /**
     * @Route("/admin/editNavbar/", methods="GET", name="admin_navbar_pages")
     */
    public function getNavbarPages()
    {
        return $this->json(
            $this->navbar->getPages(),
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/editPage/{id}", name="edit_page")
     */
    public function editPage(Request $request, Page $page)
    {
        $form = $this->createForm(PageFormType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/admin/delPage/{id}", name="del_page", methods={"DELETE"})
     */
    public function deletePage(Page $page)
    {
        $this->em->remove($page);
        $this->em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/admin/editPageSlug/{id}", name="admin_page_update_slug", methods={"PUT"})
     */
    public function updatePageSlug(Page $page, SerializerInterface $serialazer, Request $request, ValidatorInterface $validator)
    {
        $serialazer->deserialize(
            $request->getContent(),
            Page::class,
            'json',
            [
                'object_to_populate' => $page,
                'groups' => ['main']
            ]
        );

        $violations = $validator->validate($page);
        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $page->setTitleCa($page->getSlug() . '-ca');
        $page->setTitleEs($page->getSlug() . '-es');
        $page->setTitleEn($page->getSlug() . '-en');

        $this->em->persist($page);
        $this->em->flush();

        return $this->json(
            $page,
            200,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/reorderPages", methods="POST", name="admin_navbar_reorder_pages")
     */
    public function reorderNavbarPages( Request $request)
    {
        $orderedIds = json_decode($request->getContent(), true);

        if ($orderedIds === false) {
            return $this->json(['detail' => 'invalid body'], 400);
        }

        $orderedIds = array_flip($orderedIds);

        foreach ($this->navbar->getPages() as $page) {
            $page->setPosition($orderedIds[$page->getId()]);
        }

        $this->em->flush();

        return $this->json(
            $this->navbar->getPages(),
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/listPage", name="list_page")
     */
    public function listPage()
    {
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();

        return $this->render('admin/pages/listPages.html.twig', [
            'pages' => $pages,
            'navbar' => $this->navbar
        ]);
    }
}
