<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\User;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class PanelAdminController extends AbstractController
{
    #[Route('/panel/admin', name: 'app_panel_admin')]
    public function index(UserRepository $userRepository, TweetRepository $tweetRepository, CommentRepository $commentRepository, Request $request): Response
    {
        $limit = 10;
        $page = max(1, (int) $request->query->get('page', 1));
        $offset = ($page - 1) * $limit;

        $totalUsers = count($userRepository->findAll());
        $users = $userRepository->findBy([], [], $limit, $offset);

        $totalTweets = count($tweetRepository->findAll());
        $tweets = $tweetRepository->findBy([], ['creationTime' => 'DESC'], $limit, $offset);

        $totalComments = count($commentRepository->findAll());
        $comments = $commentRepository->findBy([], [], $limit, $offset);

        $signalementsCountTweets = $tweetRepository->countByIsSignaled(true);

        // Récupérer les tweets signalés spécifiquement
        $reportedTweets = [];
        if ($request->query->get('section') == 'signalementsTweets') {
            $reportedTweets = $tweetRepository->findBy(['isSignaled' => true], [], $limit, $offset);
        }

        $signalementsCountComments = $commentRepository->countByIsSignaled(true);

        // Récupérer les commentaires signalés spécifiquement
        $reportedComments = [];
        if ($request->query->get('section') == 'signalementsComments') {
            $reportedComments = $commentRepository->findBy(['isSignaled' => true], [], $limit, $offset);
        }

        return $this->render('panel_admin/index.html.twig', [
            'controller_name' => 'PanelAdminController',

            'users' => $users,
            'totalUsersCount' => $totalUsers,

            'tweets' => $tweets,
            'totalTweetsCount' => $totalTweets,

            'signalements_countTweets' => $signalementsCountTweets,
            'reportedTweets' => $reportedTweets,

            'comments' => $comments,
            'totalCommentsCount' => $totalComments,

            'signalements_countComments' => $signalementsCountComments,
            'reportedComments' => $reportedComments,

            'currentPage' => $page,

            'totalPagesUsers' => ceil($totalUsers / $limit),
            'totalPagesTweets' => ceil($totalTweets / $limit),
            'totalPagesSignalementsTweets' => ceil($signalementsCountTweets / $limit),
            'totalPagesComments' => ceil($totalComments / $limit),
            'totalPagesSignalementsComments' => ceil($signalementsCountComments / $limit),

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

        return $this->redirectToRoute('app_panel_admin', ['section' => 'signalementsTweets']);
    }

     // Vous aurez besoin d'une action pour "dé-signaler" un commentaire si vous l'ajoutez
    #[Route('/comment/{id}/unreport', name: 'app_comment_unreport', methods: ['GET'])]
    public function unreportComment(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setIsSignaled(false);
        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Le signalement du commentaire a été retiré avec succès.');

        return $this->redirectToRoute('app_panel_admin', ['section' => 'signalementsComments']);
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

    #[Route('/comment/{id}/delete', name: 'app_admin_comment_delete', methods: ['POST'])]
    public function deleteComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();

            // return new JsonResponse(['success' => true]);
        }

        // return new JsonResponse(['success' => false, 'error' => 'Invalid CSRF token'], Response::HTTP_FORBIDDEN);
        return $this->redirectToRoute('app_panel_admin', ['section' => 'commentaires']);
    }
}
