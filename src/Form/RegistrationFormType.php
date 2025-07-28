<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'],
                'label' => 'Nom :',
                'label_attr' => ['class' => 'block text-gray-700 text-sm font-bold my-2']
            ])
            ->add('firstname', null, [
                'attr' => ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'],
                'label' => 'Prenom :',
                'label_attr' => ['class' => 'block text-gray-700 text-sm font-bold my-2']
            ])
            ->add('nickname', null, [
                'attr' => ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'],
                'label' => 'Surnom :',
                'label_attr' => ['class' => 'block text-gray-700 text-sm font-bold my-2']
            ])
            ->add('age', null, [
                'attr' => ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'],
                'label' => 'Age :',
                'label_attr' => ['class' => 'block text-gray-700 text-sm font-bold my-2']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'],
                'label' => 'Email :',
                'label_attr' => ['class' => 'block text-gray-700 text-sm font-bold my-2']
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions.',
                    ]),
                ],
                'label' => 'Accepter les conditions : ',
                'label_attr' => ['class' => 'inline-flex items-center text-gray-700 text-sm font-bold my-2 cursor-pointer'],
                'row_attr' => ['class' => 'flex items-center'],
                'label_html' => true,
                'attr' => ['class' => 'ml-2'],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => "S'il vous plaît entrer un mot de passse",
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,<.>]).*$/',
                        'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un caractère spécial.',
                    ]),
                ],
                'attr' => ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'],
                'label' => 'Mot de passe :',
                'label_attr' => ['class' => 'block text-gray-700 text-sm font-bold my-2']
                ])
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
                        'attr' => ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'],
                        'label_attr' => ['class' => 'block text-gray-700 text-sm font-bold my-2']
                        ])
                        ->add('agreeTerms', CheckboxType::class, [
                            'mapped' => false,
                            'constraints' => [
                                new IsTrue([
                                    'message' => 'You should agree to our terms.',
                                ]),
                            ],
                            'label' => 'Accepter les conditions : ',
                            'label_attr' => ['class' => 'inline-flex items-center text-gray-700 text-sm font-bold my-2 cursor-pointer'],
                            'row_attr' => ['class' => 'flex items-center'],
                            'label_html' => true,
                            'attr' => ['class' => 'ml-2'],
                        ])
                        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
