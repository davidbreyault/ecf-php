<?php

namespace App\Form;

use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profession', TextType::class, [
                'label'         => 'Métier',
                'required'       => true
            ])
            ->add('company_name', TextType::class, [
                'label'         => 'Entreprise',
                'required'       => true
            ])
            ->add('workplace_town', TextType::class, [
                'label'         => 'Lieu',
                'required'       => true
            ])
            ->add('date_start', BirthdayType::class, [
                'label'         => 'Début',
                'required'      => true,
                'format'        => 'ddMMyyyy',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
            ])
            ->add('date_end', BirthdayType::class, [
                'label'         => 'Fin',
                'required'      => false,
                'format'        => 'ddMMyyyy',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label'         => 'Description'
            ])
            ->add('send', SubmitType::class, [
                'label'         => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
