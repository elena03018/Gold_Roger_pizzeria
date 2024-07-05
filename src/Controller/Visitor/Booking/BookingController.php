<?php

namespace App\Controller\Visitor\Booking;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Booking;
use App\Form\BookingFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookingTimeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    public function __construct(
        private BookingTimeRepository $bookingTimeRepository,
        private EntityManagerInterface $em
    )
    {

    }
    #[Route('/booking', name: 'visitor_booking_create', methods:['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $booking = new Booking();

        $form = $this->createForm(BookingFormType::class, $booking, [
            "times" => $this->bookingTimeRepository->findAll()
        ]);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {

            //funzione utilizzata per generare token o identificatori unici in modo sicuro.
            $code = time() . bin2hex(random_bytes(3));

            /**
             * @var User 
             */

            $user = $this->getUser();

            $booking->setUser($user);
            $booking->setCode($code);
            $booking->setCreatedAt(new DateTimeImmutable());
            $booking->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($booking);
            $this->em->flush();

            // $this->addFlash("success", "Votre demande de reservation est pris en compte, nous vous enverrons une réponse dans les prochaines par email.");
        
            return $this->redirectToRoute("visitor_booking_create_message", [
                "id" => $booking->getId()
            ]);
        }
        
        return $this->render('pages/visitor/booking/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/booking/{id<\d+>}/message', name: 'visitor_booking_create_message', methods:['GET'])]
    public function message(Booking $booking): Response
    {
        return $this->render('pages/visitor/booking/message.html.twig', [
            "booking" => $booking
        ]);
    }
}
