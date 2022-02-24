<?php

namespace App\Form;

use App\Entity\Competence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
         
        ->add('logo', FileType::class, [
            'required' => false,
            'label' => 'Photo Principale',
            'mapped' => false,
            'help' => 'png, jpg, jpeg, jp2 ou webp - 1 Mo maximum',
            'constraints' => [
                new Image([
                    'maxSize' => '1M',
                    'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} Mo). Maximum autorisé : {{ limit }} Mo.',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpg',
                        'image/jpeg',
                        'image/jp2',
                        'image/webp',
                    ],
                    'mimeTypesMessage' => 'Merci de sélectionner une image au format PNG, JPG, JPEG, JP2 ou WEBP'
                ])
            ]
        ])

        ->add('name', TextType::class, [
            'required' => true,
            'label' => 'Nom',
            'attr' => [
                'maxLength' => 100,
                'placeholder' => 'Ex.: Bootstrap, php...'
            ]
        ])
        ->add('categorie', TextType::class, [
            'required' => true,
            'label' => 'categorie',
            'attr' => [
                'maxLength' => 100,
                'placeholder' => 'cms, framework ou technologies'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
        ]);
    }
}
