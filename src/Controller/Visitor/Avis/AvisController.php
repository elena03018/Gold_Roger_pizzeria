<?php

namespace App\Controller\Visitor\Avis;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'visitor_avis_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/avis/index.html.twig');
    }
}
