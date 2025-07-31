<?php

namespace App\Controller;

use App\Entity\Like;
use App\Form\LikeType;
use App\Entity\Tweet;
use App\Entity\Comment;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/like')]
final class LikeController extends AbstractController
{
    #[Route(name: 'app_like_index', methods: ['GET'])]
    public function index(LikeRepository $likeRepository): Response
    {
        return $this->render('like/index.html.twig', [
            'likes' => $likeRepository->findAll(),
        ]);
    }

    #[Route('/tweet/{id}/like', name: 'app_tweet_like', methods: ['POST'])]
    public function toggleLike(
        Tweet $tweet,
        LikeRepository $likeRepo,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('likeTweet' . $tweet->getId(), $request->getPayload()->getString('_token'))) {

            $like = $likeRepo->findOneBy(['tweet' => $tweet, 'user' => $user]);

            if ($like) {
                $em->remove($like);
            } else {
                $like = new Like();
                $like->setTweet($tweet);
                $like->setUser($user);
                $em->persist($like);
            }

            $em->flush();
        }

        // 👉 Si la requête est AJAX, on renvoie JSON
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'tweetId' => $tweet->getId(),
                'likes' => count($tweet->getLikes())
            ]);
        }

        // Sinon fallback (navigation normale)
        return $this->redirectToRoute('app_tweet_index');
    }

    #[Route('/comment/{id}/like', name: 'app_comment_like', methods: ['POST'])]
    public function likeComment(Comment $comment, LikeRepository $likeRepo, EntityManagerInterface $em, Request $request): Response
    {

        $user = $this->getUser();
        $tweet = $comment->getTweet();
        if ($this->isCsrfTokenValid('likeComment' . $comment->getId(), $request->getPayload()->getString('_token'))) {
            $like = $likeRepo->findOneBy(['comment' => $comment, 'user' => $user]);

            if ($like) {
                $em->remove($like);
            } else {
                $like = new Like();
                $like->setComment($comment);
                $like->setUser($user);
                $like->setTweet($tweet);
                $em->persist($like);
            }

            $em->flush();
        }
        return $this->redirectToRoute('app_tweet_index');
    }
}
