<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\Query\Expr\Math;
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
                'choices'  => [
                    'Rôle utilisateur' => "ROLE_USER",
                    'Rôle administrateur' => "ROLE_ADMIN",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Sélectionner un rôle'
                    ]),
                    new Choice([
                        'message' => 'Veuillez sélectionner un rôle valide.',
                        'choices'  => ["ROLE_USER", "ROLE_ADMIN"],
                        'match' => false
                    ])
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
