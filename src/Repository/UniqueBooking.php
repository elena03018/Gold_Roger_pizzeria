<?php

namespace App\Validator;

use App\Repository\BookingTimeRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueReservationValidator extends ConstraintValidator
{
    private $reservationRepository;

    public function __construct(BookingTimeRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        // Vérifiez si une réservation existe déjà pour cette période
        $existingReservation = $this->reservationRepository->findOneBy([
            'date' => $value->getDate(), // Mettez à jour selon votre logique
            'resource' => $value->getResource() // Mettez à jour selon votre logique
        ]);

        if ($existingReservation) {
            // Si une réservation existe déjà, ajoutez une violation
            $this->context->buildViolation($constraint->message)
                ->atPath('date') // ou 'resource', selon votre logique
                ->addViolation();
        }
    }
}
