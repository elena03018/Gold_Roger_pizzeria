<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditUserRolesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('roles', ChoiceType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Select a Role'
                    ]),
                    new Choice([
                        'choices' => ['ROLE_USER', 'ROLE ADMIN' ],
                        'message' => 'Please select a valid rôle',
                    ])  
                ],
                

                'choices'  => [
                    'Rôle utilisateur' => "ROLE_USER",
                    'Rôle administrateur' => "ROLE ADMIN",
                ],
                'expanded'=> false,
                'multiple' => true
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
