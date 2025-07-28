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
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => ['class' => "block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm form-control mb-5"]
            ],)
            // ->add('roles')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => '******************',
                    'class' => "block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm form-control mb-5"
                ],
                // 'constraints' => [
                //     new NotBlank([
                //         'message' => 'Entrez votre mot de passe ici!',
                //     ]),
                //     new Length([
                //         'min' => 6,
                //         'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                //         // max length allowed by Symfony for security reasons
                //         'max' => 4096,
                //     ]),
                // ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => "block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm form control mb-5"]
            ],)
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => "block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm form control mb-5"]
            ],)
            ->add('nickName', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['class' => "block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm form control mb-5"]
            ],)
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Image trop lourde',
                    ])
                ],
                'attr' => ['class' => 'shadow appearance-none border border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-5'],
                // 'label_attr' => ['class' => 'block text-gray-700 text-sm font-bold my-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'required' => false
        ]);
    }
}
