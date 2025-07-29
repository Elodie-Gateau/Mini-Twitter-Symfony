<?php

namespace App\Controller;
use App\Entity\Tweet;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class PanelAdminController extends AbstractController
{
    #[Route('/panel/admin', name: 'app_panel_admin')]
    public function index(UserRepository $userRepository, TweetRepository $tweetRepository, Request $request): Response
    {
         $users = $userRepository->findAll();
         $tweets = $tweetRepository->findAll();
         $signalementsCount = $tweetRepository->countByIsSignaled(true);

         // Récupérer les tweets signalés spécifiquement
        $reportedTweets = [];
        if ($request->query->get('section') == 'signalements') {
            $reportedTweets = $tweetRepository->findBy(['isSignaled' => true]);
        }
        // Alternativement, vous pouvez toujours les charger si vous voulez les avoir disponibles peu importe la section.
        // $reportedTweets = $tweetRepository->findBy(['isSignaled' => true]);


        return $this->render('panel_admin/index.html.twig', [
            'controller_name' => 'PanelAdminController',
            'users' => $users,
            'tweets' => $tweets,
            'signalements_count' => $signalementsCount,
            'reportedTweets' => $reportedTweets,
        ]);
    }

        // Vous aurez besoin d'une action pour "dé-signaler" un tweet si vous l'ajoutez
    #[Route('/tweet/{id}/unreport', name: 'app_tweet_unreport', methods: ['GET'])]
    public function unreportTweet(Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        $tweet->setIsSignaled(false);
        $entityManager->persist($tweet);
        $entityManager->flush();

        $this->addFlash('success', 'Le signalement du tweet a été retiré avec succès.');

        return $this->redirectToRoute('app_panel_admin', ['section' => 'signalements']);
    }

          // Vous aurez besoin d'une action pour "bannir" un utilisateur
    #[Route('/user/{id}/ban', name: 'app_user_ban', methods: ['GET'])]
    public function banUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setIsBanned(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', "L'utilisateur a été bannis avec succès.");

        return $this->redirectToRoute('app_panel_admin', ['section' => 'users']);
    }

              // Vous aurez besoin d'une action pour "débannir" un utilisateur
    #[Route('/user/{id}/unban', name: 'app_user_unban', methods: ['GET'])]
    public function unbanUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setIsBanned(false);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', "L'utilisateur a été débannis avec succès.");

        return $this->redirectToRoute('app_panel_admin', ['section' => 'users']);
    }
    
        #[Route('/user/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('app_panel_admin', ['section' => 'users']);
    }

            #[Route('/tweet/{id}/delete', name: 'app_admin_tweet_delete', methods: ['POST'])]
    public function deleteTweet(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tweet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tweet);
            $entityManager->flush();
            $this->addFlash('success', 'Tweet supprimé avec succès.');
        }

        return $this->redirectToRoute('app_panel_admin', ['section' => 'tweets']);
    }
}
