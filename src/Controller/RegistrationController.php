<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Récupération du fichier depuis le formulaire
            $photoFile = $form->get('photo')->getData();
            // Si un fichier a été envoyé :
            if ($photoFile) {
                // On travail le nom du fichier
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // nécessaire pour inclure le nom du fichier à une partie de l'URL de manière sécurisée
                $safeFilename = $slugger->slug($originalFilename);
                // On ajoute un identifiant unique au nom du fichier pour s'assurer que deux fichiers n'aient pas le même nom
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();
                // On déplace le fichier sur le serveur
                $photoFile->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );
                // On ajoute à notre objet article
                $user->setPhoto($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $security->login($user, 'form_login', 'main');
            // Rediriger vers la page d'accueil
          
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'user' => $user,

        ]);
       
    }
}
