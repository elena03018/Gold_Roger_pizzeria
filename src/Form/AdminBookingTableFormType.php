<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\BookingTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AdminBookingTableFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $choices = [
            'en terrasse' => 'en terrasse',
            'près de la fenêtre' => 'près de la fenêtre', 
            'au centre de la salle' => 'au centre de la salle',
            'dans un coin ou table isolée' => 'dans un coin ou table isolée' ,
            'près du bar' => 'près du bar',
            'près de la cuisine' => 'près de la cuisine',
        ];

        $builder
            ->add('number', TextType::class)
            ->add('location', ChoiceType::class, [
                'choices'  => $choices,
                'constraints' => [
                    new Choice([
                        "choices" => $choices,  
                        "message" => "Cet emplacement est inconnu.",
                        "match"=>true,
                    ])
                ],
                'placeholder' => "Choisissez un emplacement",
                "expanded" => false, 
                "multiple" => false, 
            ])
            ->add('peopleNumber', NumberType::class, [
                "html5" => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookingTable::class,
        ]);
    }
}
