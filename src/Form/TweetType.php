<?php

namespace App\Form;

use App\Entity\Tweet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;
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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le contenu ne doit pas êtrer vide',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 280,
                        'minMessage' => 'Le champ doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le champ ne peut pas dépasser {{ limit }} caractères',
                        'normalizer' => 'trim',
                    ]),
                ],
            ])
            ->add('media', FileType::class, [
                'label' => 'Image de l’article',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'maxSizeMessage' => "L'image ne peut pas dépasser {{ limit }}",
                        'mimeTypes' => [
                            'media/*',
                        ],
                        'mimeTypesMessage' => 'Format invalide',
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
