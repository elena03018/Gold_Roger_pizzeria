<?php

namespace App\Controller\Admin\Comment;

use App\Entity\Comment;
use Monolog\DateTimeImmutable;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/admin/comment/list', name: 'admin_comment_index', methods:['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();

        return $this->render('pages/admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/admin/comment/{id}/enable', name: 'admin_comment_enable', methods: ['PUT'])]
    public function enable(Request $request, Comment $comment, EntityManagerInterface $em): Response
    {
        if ( $this->isCsrfTokenValid("enable_comment_" . $comment->getId(), $request->request->get('csrf_token') ) )
        {
            if( false === $comment->isEnable() )
            {
                $comment->setEnable(true);
    

                $this->addFlash('success', "Le commentaire a été publié");
            }
            else
            {
                $comment->setEnable(false);
                $comment->setDisabledAt(new DateTimeImmutable(0));
        

                $this->addFlash('success', "Le commentaire a été retiré de la liste des publications.");
            }

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('admin_comment_index');
        }
    }

    #[Route('admin/comment/{id<\d+>}/delete', name: 'admin_comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if ( $this->isCsrfTokenValid('delete_comment_'.$comment->getId(), $request->request->get('_csrf_token')) ) 
        {
            $this->addFlash('success', "Le commentaire a été supprimé.");

            // $this->em->remove($comment);
            // $this->em->flush();
                $em->remove($comment);
                $em->flush();
        }

        return $this->redirectToRoute("admin_comment_index");
    }
}
