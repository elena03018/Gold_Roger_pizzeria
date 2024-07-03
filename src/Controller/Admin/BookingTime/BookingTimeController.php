<?php

namespace App\Controller\Admin\BookingTime;

use DateTimeImmutable;
use App\Entity\BookingTime;
use App\Form\AdminBookingTimeFormType;
use App\Repository\BookingTimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin')]
class BookingTimeController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private BookingTimeRepository $bookingTimeRepository
    )
    {

    }
    
    #[Route('/booking-time', name: 'admin_bookingTime_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/booking_time/index.html.twig', [
            "bookingTimes" => $this->bookingTimeRepository->findAll()
        ]);
    }

    #[Route('/booking-time/create', name: 'admin_bookingTime_create', methods:['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $bookingTime = new BookingTime();

        $form = $this->createForm(AdminBookingTimeFormType::class, $bookingTime);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
            $bookingTime->setUser($this->getUser());
            $bookingTime->setCreatedAt(new DateTimeImmutable());
            $bookingTime->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($bookingTime);
            $this->em->flush();

            $this->addFlash("success", "Le temps a été ajouté.");

            return $this->redirectToRoute('admin_bookingTime_index');
        }
        
        return $this->render('pages/admin/booking_time/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/booking-time/{id<\d+>}edit', name: 'admin_bookingTime_edit', methods:['GET', 'POST'])]
    public function edit(BookingTime $bookingTime, Request $request): Response
    {
        
        $form = $this->createForm(AdminBookingTimeFormType::class, $bookingTime);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
            $bookingTime->setUser($this->getUser());
            $bookingTime->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($bookingTime);
            $this->em->flush();

            $this->addFlash("success", "Le temps a été modifié.");

            return $this->redirectToRoute('admin_bookingTime_index');
        }
        
        return $this->render('pages/admin/booking_time/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/booking-time/{id<\d+>}delete', name: 'admin_bookingTime_delete', methods:['POST'])]
    public function delete(BookingTime $bookingTime, Request $request): Response
    {
        
        if( $this->isCsrfTokenValid("delete_booking_time_{$bookingTime->getId()}", $request->request->get('_csrf_token')))
        {
            $this->addFlash("success", "{$bookingTime->getTime()} a été supprimé");

            $this->em->remove($bookingTime);
            $this->em->flush();
        }

        return $this->redirectToRoute("admin_bookingTime_index");
    }
}
