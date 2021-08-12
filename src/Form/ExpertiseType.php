<?php

namespace App\Form;

use App\Entity\Expertise;
use App\Entity\Technology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\SkillRepository;

class ExpertiseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('technology', EntityType::class, [
                'label'         => 'Technologie',
                'class'         => Technology::class,
                'choice_label'  => 'name',
                'required'      => true
            ])
            ->add('level', ChoiceType::class, [
                'label'         => 'Estimez votre niveau sur une échelle de 0 à 5',
                'choices'       => [
                    '0'           => 0,
                    '1'           => 1,
                    '2'           => 2,
                    '3'           => 3,
                    '4'           => 4,
                    '5'           => 5,
                ],
                'required'      => true
            ])
            ->add('appreciated', ChoiceType::class, [
                'label'         => 'Appréciez vous cette technologie ?',
                'choices'       => [
                    'Yes !'                 => 1,
                    'Bof bof...'            => 0,
                ],
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
            'data_class' => Expertise::class,
        ]);
    }
}
