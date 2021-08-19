<?php

namespace App\Form;

use App\Entity\Upload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FileType::class, [
                'label'         => 'Choisissez votre fichier',
                'constraints' => [
                    new File([ 
                    'mimeTypes' => [ // Accepte seulement les fichiers pdf
                        'application/pdf',
                        'application/x-pdf'
                    ],
                    'mimeTypesMessage' => 'This document isn\'t valid.',
                    ])
                ],
            ])
            ->add('send', SubmitType::class, [
                'label'         => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Upload::class,
        ]);
    }
}
