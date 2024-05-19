<?php

namespace App\Controller\Visitor\Menu;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    // chemin de Controller
    #[Route('/menu', name: 'visitor_menu_index', methods: ['GET'])]
    public function index(): Response
    {
        // chemin de templates
        return $this->render('pages/visitor/menu/index.html.twig');
    }
}
