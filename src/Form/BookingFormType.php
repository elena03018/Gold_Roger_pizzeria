<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Booking;
use App\Entity\BookingTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $times = $options['times'];
        $timeFormatted = [];

        foreach ($times as $time) 
        {
            $time = $time->getTime();
            $timesFormatted[$time] = $time;
        }

        $peopleNumbers = [
            "1" => 1,
            "2" => 2,
            "3" => 3,
            "4" => 4,
        ];

        $purposes = [
            "Dîner en famille" => "Dîner en famille", 
            "Réunion d'affaires" => "Réunion d'affaires",
            "Anniversaire" => "Anniversaire", 
            "Réunion entre amis" => "Réunion entre amis", 
            "Soirée romantique" => "Soirée romantique",
            "Dîner de travail" => "Dîner de travail",
            "Célébration de réussite" => "Célébration de réussite",
            "Dîner avant/après spectacle" => "Dîner avant/après spectacle", 
            "autre" => "autre"
        ];

        //dd($timesFormatted);

        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('time', ChoiceType::class, [
                'choices'  => $timesFormatted,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => "Choisissez l'heure",
                'constraints' => [
                    new Choice([
                        "choices" => $timesFormatted,  
                        "message" => "Cet temps est invalide.",
                        "match"=>true,
                    ])
                ],
            ])
            ->add('peopleNumber', ChoiceType::class, [
                'choices'  => $peopleNumbers,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => "Choisissez le nombre de personnes",
                'constraints' => [
                    new Choice([
                        "choices" => $peopleNumbers,  
                        "message" => "Cet temps est invalide.",
                        "match"=>true,
                    ])
                ],
            ])
            ->add('purpose', ChoiceType::class, [
                "placeholder" => "Choisissez une occasion",
                'choices'  => $purposes,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => "Choisissez une occasion",
                'constraints' => [
                    new Choice([
                        "choices" => $purposes,  
                        "message" => "Cette occasion est inconnue.",
                        "match"=>true,
                    ])
                ],
            ])
            ->add('details', TextareaType::class)
            


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'times' => []
        ]);
    }
}
