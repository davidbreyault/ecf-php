<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Technology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TechnologyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'         => 'Nom',
                'required'      => true
            ])
            ->add('category', EntityType::class, [
                'label'         => 'Catégorie',
                'class'         => Category::class,
                'multiple'      => false,   
                'choice_label'  => 'name',
                'required'      => true,
            ])
            ->add('send', SubmitType::class, [
                'label'         => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Technology::class,
        ]);
    }
}
