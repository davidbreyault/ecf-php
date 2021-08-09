<?php

namespace App\Form;

use App\Entity\Skill;
use App\Repository\SkillRepository;
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
            ->add('skill', EntityType::class, [
                'label'         => 'Compétence',
                'class'         => Skill::class,
                'multiple'      => false,   
                'choice_label'  => 'name',
                'required'      => false,
                'query_builder' => function(SkillRepository $er) {
                    return $er->getDistinctValues();
                }
            ])
            ->add('level', ChoiceType::class, [
                'label'         => 'Sélectionner le niveau de compétence à rechercher',
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
