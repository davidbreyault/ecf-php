<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
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
            ->add('send', SubmitType::class, [
                'label'         => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
