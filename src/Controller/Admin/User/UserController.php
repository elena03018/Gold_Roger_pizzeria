<?php

namespace App\Controller\Admin\User;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\EditUserRolesFormType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/admin/user/{id<\d+>}/edit/roles', name: 'admin_user_edit_roles', methods: ['GET', 'PUT'])]
    public function editRoles(User $user, Request $request, EntityManagerInterface $em): Response 
    {
        $form = $this->createForm(EditUserRolesFormType::class, $user, [
            "method" => "PUT"
        ]);
        
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            $em->persist($user);
            $em->flush();  

            $this->addFlash("success", "Les rôles de {$user->getFirstName()} {$user->getLastName()} ont été modifié avec succèss.");

            return $this->redirectToRoute("admin_user_index");
        }

        return $this->render('pages/admin/user/edit_roles.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/user/{id<\d+>}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ( $this->isCsrfTokenValid('delete_user_'.$user->getId(), $request->request->get('csrf_token')) ) 
        {

            $this->addFlash('success', "{$user->getFirstName()} {$user->getLastName()} a été supprimé!");

            $post = $user->getPosts();

            foreach ($posts as $post) 
            {
                $post->setUser(null);
            }
            
            $this->container->get('security.token_storage')->setToken(null);

            $em->remove($user);
            $em->flush();

        }

        return $this->redirectToRoute("admin_user_index");
    }
}
