<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use DateTimeImmutable;
use App\Form\PostFormType;
use App\Form\PostFormTpeType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{

    public function __construct(
        private CategoryRepository $categoryRepository
    )
    {
        
    }
    #[Route('/admin/post/list', name: 'admin_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $post = $postRepository->findAll();
        return $this->render('pages/admin/post/index.html.twig', [
            "posts" => $post
        ]);
    }

    #[Route('/admin/post/create', name: 'admin_post_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response 
    {

        if ( count($this->categoryRepository->findAll()) <= 0 ) 
        {
            $this->addFlash('warning', "Pour ajouter des pizzas, vous devez créer au moins une catégorie");
            return $this->redirectToRoute('admin_category_index');
        }

        $post = new Post();

        $form = $this->createForm(PostFormTpeType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $admin = $this->getUser();

            $post->setUser($admin);
            $post->setPublished(false);

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', "Parfait pirata! La pizza a été ajoutée!");

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('pages/admin/post/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/post/{id}/publish', name: 'admin_post_publish', methods: ['PUT'])]
    public function publish(Request $request, Post $post, EntityManagerInterface $em): Response
    {
        if ( $this->isCsrfTokenValid("publish_post_" . $post->getId(), $request->request->get('csrf_token') ) )
        {
            if( false === $post->isPublished() )
            {
                $post->setPublished(true);
                $post->setPublishedAt(new DateTimeImmutable());

                $this->addFlash('success', "La pizza a été publiée");
            }
            else
            {
                $post->setPublished(false);
                $post->setPublishedAt(null);

                $this->addFlash('success', "La pizza a été retirée de la liste des publications.");
            }

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('admin_post_index');
        }
    }

    #[Route('/admin/post/{id}/edit', name: 'admin_post_edit', methods: ['GET', 'PUT'])]
    public function edit(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PostFormTpeType::class, $post, [
            "method" => "PUT"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $post->setUpdatedAt(new DateTimeImmutable());

            $admin = $this->getUser();

            $post->setUser($admin);
        

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', "L'objet a été modifié et sauvegardé. ");

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('pages/admin/post/edit.html.twig', [
            "post" => $post,
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/post/{id<\d+>}/delete', name: 'admin_post_delete', methods: ['POST'])]
    public function delete(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        if ( $this->isCsrfTokenValid("delete_post_{$post->getId()}", $request->request->get('_csrf_token')))
        {
            $em->remove($post);
            $em->flush();

            $this->addFlash("success", "L'objet a été supprimé. ");
        }

        return $this->redirectToRoute("admin_post_index");
    }

}
