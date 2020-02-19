<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function portofolio()
    {
        return $this->render('admin/portofolio.html.twig');
    }
}
