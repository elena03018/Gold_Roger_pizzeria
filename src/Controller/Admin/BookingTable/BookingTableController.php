<?php

namespace App\Controller\Admin\BookingTable;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\BookingTable;
use App\Form\AdminBookingTableFormType;
use App\Repository\BookingTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class BookingTableController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private BookingTableRepository $bookingTableRepository
    )
    {

    }
    #[Route('/booking-table', name: 'admin_bookingTable_index', methods:['GET'])]   //evenement
    public function index(): Response
    {
        return $this->render('pages/admin/booking_table/index.html.twig', [
            "bookingTables" => $this->bookingTableRepository->findAll()
        ]);
    }

    #[Route('/booking-table/create', name: 'admin_bookingTable_create', methods:['GET', 'POST'])]   //evenement
    public function create(Request $request): Response
    {
        $bookingTable = new BookingTable();

        $form = $this->createForm(AdminBookingTableFormType::class, $bookingTable);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
            /**
             * @var User
             */
            $admin = $this->getUser();

            $bookingTable->setUser($admin);
            $bookingTable->setCreatedAt(new DateTimeImmutable());
            $bookingTable->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($bookingTable);
            $this->em->flush();

            $this->addFlash("success", "La table {$bookingTable->getNumber()} a été ajouté.");

            return $this->redirectToRoute('admin_bookingTable_index');
        }
        
        return $this->render('pages/admin/booking_table/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/booking-table/{id<\d+>}/edit', name: 'admin_bookingTable_edit', methods:['GET', 'POST'])]   //evenement
    public function edit(BookingTable $bookingTable, Request $request): Response
    {

        $form = $this->createForm(AdminBookingTableFormType::class, $bookingTable);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
            /**
             * @var User
             */
            $admin = $this->getUser();

            $bookingTable->setUser($admin);
            $bookingTable->setCreatedAt(new DateTimeImmutable());
            $bookingTable->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($bookingTable);
            $this->em->flush();

            $this->addFlash("success", "La table {$bookingTable->getNumber()} a été ajouté.");

            return $this->redirectToRoute('admin_bookingTable_index');
        }
        
        return $this->render('pages/admin/booking_table/edit.html.twig', [
            "form" => $form->createView(), 
            "bookingTable" =>$bookingTable
        ]);
    }

    #[Route('/booking-table/{id<\d+>}delete', name: 'admin_bookingTable_delete', methods:['POST'])]
    public function delete(BookingTable $bookingTable, Request $request): Response
    {
        
        if( $this->isCsrfTokenValid("delete_booking_table_{$bookingTable->getId()}", $request->request->get('_csrf_token')))
        {
            $this->addFlash("success", "La table {$bookingTable->getNumber()} a été supprimé");

            $this->em->remove($bookingTable);
            $this->em->flush();
        }

        return $this->redirectToRoute("admin_bookingTable_index");
    }
}
