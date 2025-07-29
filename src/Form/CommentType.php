<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Tweet;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'constraints' => [
                        new NotBlank([
                            'message' => 'Vous devez écrire du texte',
                        ]),
                        new Length([
                            'min' => 8,
                            'max' => 280,
                            'minMessage' => 'Le champ doit contenir au moins {{ limit }} caractères',
                            'maxMessage' => 'Le champ ne peut pas dépasser {{ limit }} caractères',
                            'normalizer' => 'trim',
                        ]),
                    ],
                'attr' => [
                    'class' => "block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500",
                ],
                'label' => false,
            ])
            // ->add('dateTime')
            // ->add('tweet', EntityType::class, [
            //     'class' => Tweet::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])

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
            'data_class' => Comment::class,
            'required' => false,
        ]);
    }
}
