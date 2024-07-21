<?php

namespace App\Controller\Admin\Booking;

use App\Entity\Booking;
use App\Entity\BookingTable;
use App\Service\SendEmailService;
use App\Form\AdminBookingFormType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')] //per la sicurezza si imposta che tutte le routes saranno relative allo spazio admin
class BookingController extends AbstractController
{

    public function __construct(
        private BookingRepository $bookingRepository, 
        private EntityManagerInterface $em,
        private SendEmailService $sendEmailService
    ){

    }

    #[Route('/booking/list', name: 'admin_booking_index', methods:['GET'])]
    public function index(): Response
    {
        $bookings = $this->bookingRepository->findAll();
        
        return $this->render('pages/admin/booking/index.html.twig', compact('bookings'));
        // return $this->render('pages/admin/booking/index.html.twig', [
        //     "booking" => $bookings
        // ]);
    }

    // #[Route('/booking/{id<\d+>}/process', name: 'admin_booking_process', methods:['GET', 'POST'])]
    // public function process(Booking $booking, Request $request): Response
    // {
    //     $form = $this->createForm(AdminBookingFormType::class, $booking);

    //     $form->handleRequest($request);

    //     if( $form->isSubmitted() && $form->isValid() )
    //     {
    //         //dd($booking->getStatus());
    //         //Mettre à jour le statut de la réservation

    //         //Mettre à jour le statut de la table
    //         $bookingTable = $booking->getBookingTable();

    //         if( Booking::STATUS_IS_VALID === $booking->getStatus() )
    //         {
    //             $bookingTable->setStatus(BookingTable::STATUS_IS_NOT_AVAILABLE);

    //             //Envoyer l'email
    //             $this->sendEmailService->send([
    //                 "sender_email" => "goldrogerpizzeria@gmail.com", 
    //                 "sender_name" => "Elena Bellu",
    //                 "recipient_email" => $booking->getUser()->getEmail(),
    //                 "subject" =>"Réponse à votre demande de réservation sur Gold Roger Pizzeria",
    //                 "html_template" => "emails/booking_email_response.html.twig",
    //                 "context" => [
    //                     "booking_first_name"    => $booking->getUser()->getFirstName(),
    //                     "booking_last_name"     => $booking->getUser()->getLastName(),
    //                     "booking_code"          => $booking->getCode(),
    //                     "booking_date"          => $booking->getDate(),
    //                     "booking_time"          => $booking->getTime(),
    //                     "booking_table"          =>$booking->getBookingTable() ?? null,
    //                 ]
    //             ]);

    //         }

    //         if( Booking::STATUS_IS_END === $booking->getStatus() )
    //         {
    //             $bookingTable->setStatus(BookingTable::STATUS_IS_AVAILABLE);
    //         }
            
    //         // se la prenotazione non è stata accettata

    //         if (Booking::STATUS_IS_NOT_VALID === $booking->getStatus()) {
    //             // Invio dell'email di rifiuto
    //             $this->sendEmailService->send([
    //                 "sender_email" => "goldrogerpizzeria@gmail.com",
    //                 "sender_name" => "Elena Bellu",
    //                 "recipient_email" => $booking->getUser()->getEmail(),
    //                 "subject" => "Réponse à votre demande de réservation sur Gold Roger Pizzeria",
    //                 "html_template" => "emails/booking_email_rejection.html.twig",
    //                 "context" => [
    //                     "booking_first_name"    => $booking->getUser()->getFirstName(),
    //                     "booking_last_name"     => $booking->getUser()->getLastName(),
    //                     "message"               => "Malheureusement, votre demande de réservation a été rejetée. Nous vous invitons à effectuer une nouvelle réservation pour une autre date qui vous convient ou à nous appeler au 0987654321 pour vérifier ensemble les disponibilités. Merci!",
    //                 ]
    //             ]);
    //         }


    //         //Vérifier si le booking table correspondante existe vraiment

    //         //dd($form)

    //         //Charger le manager des entités

    //         //Mettre à jour Persistenza delle modifiche
    //         $this->em->persist($booking);
    //         $this->em->persist($bookingTable);
    //         $this->em->flush();

            

    //         //Message flash per confermare che la prenotazione è stata trattata
    //         $this->addFlash("success", "La réservation {$booking->getId()} est traitée");
    //         //Rediriger Reindirizzamento alla pagina delle prenotazioni amministrative
    //         return $this->redirectToRoute("admin_booking_index");
    //     }

    //     return $this->render('pages/admin/booking/process.html.twig', [
    //         "form" => $form->createView(), 
    //         "booking" => $booking
    //     ]);
    // }

    #[Route('/booking/{id<\d+>}/process', name: 'admin_booking_process', methods:['GET', 'POST'])]
    public function process(Booking $booking, Request $request): Response
    {
        $form = $this->createForm(AdminBookingFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Assicurati che la prenotazione e il tavolo associato non siano NULL
            if (!$booking) {
                throw $this->createNotFoundException('Réservation non trouvée');
            }

            $bookingTable = $booking->getBookingTable();

            // Gestisci lo stato della prenotazione e invia le email
            if (Booking::STATUS_IS_VALID === $booking->getStatus()) {
                if ($bookingTable) {
                    $bookingTable->setStatus(BookingTable::STATUS_IS_NOT_AVAILABLE);

                    // Persisti anche il tavolo se esiste
                    $this->em->persist($bookingTable);
                }

                // Invio dell'email di conferma
                $this->sendEmailService->send([
                    "sender_email" => "goldrogerpizzeria@gmail.com",
                    "sender_name" => "Elena Bellu",
                    "recipient_email" => $booking->getUser()->getEmail(),
                    "subject" => "Réponse à votre demande de réservation sur Gold Roger Pizzeria",
                    "html_template" => "emails/booking_email_response.html.twig",
                    "context" => [
                        "booking_first_name"    => $booking->getUser()->getFirstName(),
                        "booking_last_name"     => $booking->getUser()->getLastName(),
                        "booking_code"          => $booking->getCode(),
                        "booking_date"          => $booking->getDate(),
                        "booking_time"          => $booking->getTime(),
                        "booking_table"         => $bookingTable,
                    ]
                ]);
            } elseif (Booking::STATUS_IS_END === $booking->getStatus()) {
                if ($bookingTable) {
                    $bookingTable->setStatus(BookingTable::STATUS_IS_AVAILABLE);

                    // Persisti anche il tavolo se esiste
                    $this->em->persist($bookingTable);
                }
            } elseif (Booking::STATUS_IS_NOT_VALID === $booking->getStatus()) {
                // Invio dell'email di rifiuto
                $this->sendEmailService->send([
                    "sender_email" => "goldrogerpizzeria@gmail.com",
                    "sender_name" => "Elena Bellu",
                    "recipient_email" => $booking->getUser()->getEmail(),
                    "subject" => "Réponse à votre demande de réservation sur Gold Roger Pizzeria",
                    "html_template" => "emails/booking_email_rejection.html.twig",
                    "context" => [
                        "booking_first_name"    => $booking->getUser()->getFirstName(),
                        "booking_last_name"     => $booking->getUser()->getLastName(),
                        "message"               => "Malheureusement, votre demande de réservation a été rejetée. Nous vous invitons à effectuer une nouvelle réservation pour une autre date qui vous convient ou à nous appeler au 0987654321 pour vérifier ensemble les disponibilités. Merci!",
                    ]
                ]);
            } else {
                throw new \Exception('Statut de réservation non reconnu');
            }

            // Persisti la prenotazione
            $this->em->persist($booking);

            // Flushing delle modifiche
            $this->em->flush();

            // Messaggio flash per confermare che la prenotazione è stata trattata
            $this->addFlash("success", "La réservation {$booking->getId()} a été traitée.");

            // Reindirizzamento alla pagina delle prenotazioni amministrative
            return $this->redirectToRoute("admin_booking_index");
        }

        // Renderizzazione della vista con il form e la prenotazione
        return $this->render('pages/admin/booking/process.html.twig', [
            "form" => $form->createView(),
            "booking" => $booking
        ]);
    }


    #[Route('/booking/{id<\d+>}/delete', name: 'admin_booking_delete', methods:['POST'])]
    public function delete(Booking $booking, Request $request): Response
    {
        if( $this->isCsrfTokenValid("delete_booking_{$booking->getId()}", $request->request->get('_csrf_token')))
        {
            $this->addFlash("success", "La table {$booking->getId()} a été supprimé");

            $bookingTable = $booking->getBookingTable();
            $bookingTable->setStatus(BookingTable::STATUS_IS_AVAILABLE);


            $this->em->remove($booking);
            $this->em->flush();
        }

        return $this->redirectToRoute("admin_booking_index");
    }
}
