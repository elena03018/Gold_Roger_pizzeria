<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\EditUserRolesFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/admin/user/list', name: 'admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('pages/admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/{id<\d+>}/edit/roles', name: 'admin_user_edit_roles', methods: ['GET'])]
    public function editRoles(User $user): Response 
    {
        $form = $this->createForm(EditUserRolesFormType::class, $user);

        return $this->render('pages/admin/user/edit_roles.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
