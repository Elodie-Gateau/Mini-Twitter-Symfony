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

class TweetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' =>
                [
                    'maxlength' => 280,
                ],
            ])
            ->add('media', FileType::class, [
                'label' => 'Image de l’article',
                'mapped' => false,
                'required' => false,
                // 'constraints' => [
                //     new File([
                //         // 'maxSize' => '5000k',
                //         'mimeTypes' => [
                //             'media/*',
                //         ],
                //         'mimeTypesMessage' => 'Image trop lourde',
                //     ]),
                // ],
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
