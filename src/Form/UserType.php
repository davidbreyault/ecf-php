<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'         => 'E-mail',
                'required'      => true
            ])
            ->add('password', RepeatedType::class, [
                'type'              => PasswordType::class,
                'invalid_message'   => 'Le mot de passe et la confirmation doivent être identique',
                'required'        => true,
                'first_options'   => [
                    'label' => 'Mot de passe'
                ],
                'second_options'  => [
                    'label' => 'Confirmez le mot de passe'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label'         => 'Prénom',
                'required'      => true
            ])
            ->add('lastname', TextType::class, [
                'label'         => 'Nom',
                'required'      => true
            ])
            ->add('gender', ChoiceType::class, [
                'label'         => 'Genre',
                'required'      => true,
                'choices'       => [
                    'Masculin'          => 'M',
                    'Féminin'           => 'F'
                ]
            ])
            ->add('birth', BirthdayType::class, [
                'label'         => 'Date de naissance',
                'required'      => true,
                'format'        => 'ddMMyyyy',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
            ])
            ->add('address', TextType::class, [
                'label'         => 'Adresse',
                'required'      => true
            ])
            ->add('postcode', TextType::class, [
                'label'         => 'Code Postal',
                'required'      => true
            ])
            ->add('town', TextType::class, [
                'label'         => 'Ville',
                'required'      => true
            ])
            ->add('phone_number', TextType::class, [
                'label'         => 'Téléphone',
                'required'      => true
            ])
            ->add('availability', DateType::class, [
                'label'         => 'Disponibilité',
                'required'      => true,
                'format'        => 'ddMMyyyy',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
            ])
            ->add('is_employed', ChoiceType::class, [
                'label'         => 'Embauché',
                'required'      => true,
                'choices'       => [
                    'Oui'          => 1,
                    'Non'          => 0
                ]
            ])
            ->add('send', SubmitType::class, [
                'label'     => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
