<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\String\Slugger\SluggerInterface;




#[Route('/profile')]
final class ProfileController extends AbstractController
{

    #[Route('/{id}', name: 'app_profile_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if ($user !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Vous n\'avez pas la permission de voir ce profil.');
        }
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    
        // return $this->render('tweet/show.html.twig', [
        //     'tweet' => $tweet,
        // ]);
    }

    #[Route('/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($user !== $this->getUser()) {
            throw new AccessDeniedException('Vous n\'avez pas la permission de modifier ce profil.');
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $photoFile = $form->get('photo')->getData();
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
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('app_profile_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
