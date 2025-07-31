<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Tweet;
use App\Repository\TweetRepository;
use App\Form\UserType;
use App\Repository\LikeRepository;
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
    public function show(User $user, Tweet $tweet, TweetRepository $tweetRepository, LikeRepository $likeRepository, Request $request): Response
    {
        $limit = 5;
        $page = max(1, (int) $request->query->get('page', 1));
        $offset = ($page - 1) * $limit;

        $paginationDataTweets = $tweetRepository->findPaginatedByUser($user, $limit, $offset);
        $tweets = $paginationDataTweets['tweets'];
        $totalTweets = $paginationDataTweets['totalCountTweets'];

         $paginationDataLikes = $likeRepository->findPaginatedByUser($user, $limit, $offset);
        $likes = $paginationDataLikes['likes'];
        $totalLikes = $paginationDataLikes['totalCountLikes'];

          // --- DÉBUT DES LIGNES DE DÉBOGAGE ---
        // dump('--- Debugging Pagination ---');
        //     dump('PaginatationData:', $paginationData);
        // dump('User ID:', $user->getId());
        // dump('Current Page:', $page);
        // dump('Limit per page:', $limit);
        // dump('Offset:', $offset);
        // dump('Total Tweets for this User (from repository):', $totalTweets);
        // dump('Number of Tweets returned for this page:', count($tweets));
        // dump('Calculated Total Pages:', ceil($totalTweets / $limit));
        // dump('--- End Debugging ---');
        // --- FIN DES LIGNES DE DÉBOGAGE ---
    
        // if ($user !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
        //     throw new AccessDeniedException('Vous n\'avez pas la permission de voir ce profil.');
        // }
        return $this->render('profile/show.html.twig', [
            'currentPage' => $page,

            'user' => $user,

            'tweets' => $tweets,
            'totalTweetsCount' => $totalTweets,
            'totalPagesTweets' => ceil($totalTweets / $limit),

            'likes' => $likes,
            'totalLikesCount' => $totalLikes,
            'totalPagesLikes' => ceil($totalLikes / $limit),
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
