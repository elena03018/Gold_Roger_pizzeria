<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminPostPostController extends AbstractController
{
    #[Route('/admin/post/post', name: 'app_admin_post_post')]
    public function index(): Response
    {
        return $this->render('admin_post_post/index.html.twig', [
            'controller_name' => 'AdminPostPostController',
        ]);
    }
}
