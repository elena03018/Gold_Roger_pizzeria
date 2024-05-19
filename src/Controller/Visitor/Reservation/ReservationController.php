<?php

namespace App\Controller\Visitor\Reservation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'visitor_reservation_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/reservation/index.html.twig');
    }
}
