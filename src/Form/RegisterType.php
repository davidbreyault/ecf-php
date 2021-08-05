<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'         => 'E-mail',
                'required'      => true
            ])
            ->add('firstname', TextType::class, [
                'label'         => 'Prénom',
                'required'      => true
            ])
            ->add('lastname', TextType::class, [
                'label'         => 'Nom',
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
                    'label' => 'Confirmez votre mot de passe'
                ]
            ])
            ->add('Envoyer', SubmitType::class, [
                'label'             => 'Inscription'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
