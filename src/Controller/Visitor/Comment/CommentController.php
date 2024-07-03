<?php

namespace App\Controller\Visitor\Comment;

use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    // #[Route('/comment', name: 'visitor_comment_index', methods:['GET'])]
    // public function index(): Response
    // {
    //     return $this->render('pages/visitor/comment/index.html.twig');
    // }

    #[Route('/comment', name: 'visitor_comment_index', methods:['GET', 'POST'])]
    public function index(
        Request $request, 
        EntityManagerInterface $em,
        CommentRepository $commentRepository
        ):Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            $comment->setUser($this->getUser());
            $comment->setEnable(true);
            $comment->setCreatedAt(new DateTimeImmutable());
        
            
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('visitor_comment_index');
        }

        return $this->render('pages/visitor/comment/index.html.twig', [
            "form" => $form->createView(),
            "comments"  => $commentRepository->findAll()
        ]);
    }
}
