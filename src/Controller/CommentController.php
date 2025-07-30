<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Media;
use App\Entity\Tweet;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/comment')]
final class CommentController extends AbstractController
{
    #[Route(name: 'app_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/{id}/comment', name: 'app_tweet_comments_index', methods: ['GET', 'POST'])]
    public function TweetComments(
        Request $request,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager,
        Security $security,
        SluggerInterface $slugger,
        int $id
    ): Response {
        $tweet = $entityManager->getRepository(Tweet::class)->find($id);

        if (!$tweet) {
            throw $this->createNotFoundException("Tweet non trouvé.");
        }

        // PAGINATION
        $limit = 5;
        $page = max(1, (int) $request->query->get('page', 1));
        $offset = ($page - 1) * $limit;

        $totalComments = $commentRepository->count(['tweet' => $tweet]);
        $comments = $commentRepository->findBy(['tweet' => $tweet], ['dateTime' => 'DESC'], $limit, $offset);

        // FORMULAIRE DE NOUVEAU COMMENTAIRE
        $comment = new Comment();
        $comment->setTweet($tweet);
        $comment->setUser($security->getUser());
        $comment->setDateTime(new \DateTime());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            $imageFile = $form->get('media')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $media = new \App\Entity\Media;
                $media->setUrlMedia('/uploads/images/' . $newFilename);
                $media->setComment($comment);

                $entityManager->persist($media);
                $entityManager->flush();
                $comment->addMedium($media);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_comments_paginated', [
                'id' => $tweet->getId(),
                'page' => 1
            ]);
        }

        return $this->render('tweet/index.html.twig', [
            'tweets' => [$tweet],
            'selectedTweet' => $tweet,
            'comments' => $comments,
            'commentForm' => $form->createView(),
            'commentCurrentPage' => $page,
            'commentTotalPages' => ceil($totalComments / $limit),
        ]);
    }

    #[Route('/new/{tweetId}', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(int $tweetId, Request $request, EntityManagerInterface $entityManager, Security $security, SluggerInterface $slugger): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $tweet = $entityManager->getRepository(Tweet::class)->find($tweetId);
        $comment->setTweet($tweet);
        $comment->setUser($security->getUser());
        $comment->setDateTime(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($comment);
            $entityManager->flush();

            $imageFile = $form->get('media')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $media = new Media;
                $media->setUrlMedia('/uploads/images/' . $newFilename);
                $media->setComment($comment);

                $entityManager->persist($media);
                $entityManager->flush();
                $comment->addMedium($media);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager, Security $security, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $imageFile = $form->get('media')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $media = new Media;
                $media->setUrlMedia('/uploads/images/' . $newFilename);
                $media->setComment($comment);

                $entityManager->persist($media);
                $entityManager->flush();
                $comment->addMedium($media);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();

            // return new JsonResponse(['success' => true]);
        }

        // return new JsonResponse(['success' => false, 'error' => 'Invalid CSRF token'], Response::HTTP_FORBIDDEN);
        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }

    // SIGNALER UN TWEET

    #[Route('/{id}/signal', name: 'app_comment_signalComment', methods: ['POST'])]
    public function signalComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {


        if ($this->isCsrfTokenValid('signalComment' . $comment->getId(), $request->getPayload()->getString('_token'))) {

            $comment->setIsSignaled(true);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Ce commentaire a bien été signalé, il est en attente de modération !');
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/tweet/{id}/comments', name: 'app_tweet_comments_ajax', methods: ['GET'])]
    public function loadComments(Tweet $tweet, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(
            ['tweet' => $tweet],
            ['dateTime' => 'DESC']
        );

        // On renvoie un fragment Twig contenant uniquement les commentaires
        return $this->render('comment/_comments_list.html.twig', [
            'comments' => $comments,
            'tweet' => $tweet
        ]);
    }
}
