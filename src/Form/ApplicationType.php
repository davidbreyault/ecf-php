<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'         => 'Prénom',
                'required'      => true
            ])
            ->add('lastname', TextType::class, [
                'label'         => 'Nom',
                'required'      => true
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
            ->add('availability', ChoiceType::class, [
                'label'         => 'Disponibilité',
                'choices'       => [
                    'Immédiate'             => 'Immédiate',
                    'Dans 1 mois'           => 'Dans 1 mois',
                    'Dans 3 mois'           => 'Dans 3 mois',
                    'Supérieure à 3 mois'   => 'Supérieure à 3 mois'
                ]
            ])
            ->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
