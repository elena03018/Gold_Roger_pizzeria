<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Booking;
use App\Entity\BookingTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminBookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [
            "validée" => "validée",
            "non validée" => "non validée",
            "cloturée" => "cloturée"
        ];
        $builder
            ->add('status', ChoiceType::class, [
                'placeholder' => "Changez le statut de la réservation",
                'choices'  => $choices,
                'constraints' => [
                    new NotBlank([
                        "message" => "Le statut est obligatoire."
                    ]),
                    new Choice([
                        "choices" => $choices,  
                        "message" => "Le statut est inconnue.",
                        "match"=>true,
                    ])
                ],
            ])
            ->add('bookingTable', EntityType::class, [
                'placeholder' => "Selectionnez la table correspondante",
                'class' => BookingTable::class,
                //'choice_label' => 'number',
                'multiple' => false,
                'expanded' => false,
                'constraints' => [
                // new NotBlank([
                //         "message" => "La table est obligatoire."
                //     ]),
                    new Type([
                        'type'=> BookingTable::class,
                        'message' => "Cette table est inconnue", 
                    ])
                    ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
