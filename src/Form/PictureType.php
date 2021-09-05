<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FileType::class, [
                'label'         => 'Choisissez votre photo',
                'constraints'   => [
                    new File([ 
                    // Accepte seulement les fichiers jpeg et png de moins de 1024ko
                    'maxSize'   => '1024k',
                    'mimeTypes' => [ 
                        'image/jpeg',
                        'image/png'
                    ],
                    'mimeTypesMessage' => 'Please upload a valid img file.',
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
            'data_class' => Picture::class,
        ]);
    }
}
