<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, ['label' => 'Votre email',],)
            // ->add('roles')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Votre mot de passe',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe ici!',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('name', TextType::class, ['label' => 'Votre nom',],)
            ->add('firstName', TextType::class, ['label' => 'Votre prénom',],)
            ->add('nickName', TextType::class, ['label' => 'Votre pseudo',],)
            // ->add('photo', FileType::class, [
            //     'label' => 'Photo de profil',
            //     'mapped' => false,
            //     'required' => false,
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '5000k',
            //             'mimeTypes' => [
            //                 'image/*',
            //             ],
            //             'mimeTypesMessage' => 'Image trop lourde',
            //         ])
            //     ],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
