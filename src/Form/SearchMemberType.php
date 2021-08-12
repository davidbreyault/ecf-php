<?php

namespace App\Form;

use App\Entity\Technology;
use App\Repository\TechnologyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SearchMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'         => 'Nom',
                'required'      => false
            ])
            ->add('technology', EntityType::class, [
                'label'         => 'Technologie',
                'class'         => Technology::class,
                'multiple'      => false,   
                'choice_label'  => 'name',
                'required'      => false,
                'query_builder' => function(TechnologyRepository $er) {
                    return $er->sortTechnologies();
                }
            ])
            ->add('level', ChoiceType::class, [
                'label'         => 'Niveau de compétence',
                'choices'       => [
                    '0'           => 0,
                    '1'           => 1,
                    '2'           => 2,
                    '3'           => 3,
                    '4'           => 4,
                    '5'           => 5,
                ],
                'required'      => false
            ])
            ->add('send', SubmitType::class, [
                'label'         => 'Rechercher'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
