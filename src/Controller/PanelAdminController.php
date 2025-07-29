<?php

namespace App\Controller;
use App\Entity\Tweet;
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
    
}
