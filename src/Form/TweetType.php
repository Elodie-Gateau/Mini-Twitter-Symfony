<?php

namespace App\Form;

use App\Entity\Tweet;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TweetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Message (280 caractères max.)',
                'attr' => [
                    'rows' => 5,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez écrire du texte',
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 280,
                        'minMessage' => 'Le champ doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le champ ne peut pas dépasser {{ limit }} caractères',
                        'normalizer' => 'trim',
                    ]),
                ],
            ])
            ->add('media', FileType::class, [
                'label' => 'Ajouter un média',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'maxSizeMessage' => "L'image ne peut pas dépasser {{ limit }} Mo",
                        'mimeTypes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif', 'video/mp4'],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier de format jpg, jpeg, png, webp, gif ou mp4',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tweet::class,
            'required' => false,
        ]);
    }
}