<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\Media;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\SecurityBundle\Security;


// TRI DES TWEETS PAR PAGINATION

#[Route('/tweet')]
final class TweetController extends AbstractController
{
    #[Route(name: 'app_tweet_index', methods: ['GET'])]
    public function index(TweetRepository $tweetRepository, Request $request): Response
    {

        $limit = 5;
        $page = max(1, (int) $request->query->get('page', 1));
        $offset = ($page - 1) * $limit;

        $totalTweets = count($tweetRepository->findAll());
        $tweets = $tweetRepository->findBy([], ['creationTime' => 'DESC'], $limit, $offset);

        return $this->render('tweet/index.html.twig', [
            'tweets' => $tweets,
            'currentPage' => $page,
            'totalPages' => ceil($totalTweets / $limit),
        ]);
    }

    // AFFICHE LES COMMENTAIRES EN AJAX
    #[Route('/{id}/comments', name: 'app_tweet_comments_ajax', methods: ['GET'])]
    public function loadComments(Tweet $tweet): Response
    {
        return $this->render('comment/_comments_list.html.twig', [
            'comments' => $tweet->getComment(),
        ]);
    }

    // AJOUTER UN COMMENTAIRE A UN TWEET SUR LA PAGE D'ACCUEIL

    #[Route('/{id}/comment', name: 'app_tweet_index_comment', methods: ['GET', 'POST'])]
    public function index_comment(int $id, TweetRepository $tweetRepository, Request $request, EntityManagerInterface $em): Response
    {
        // $tweets = $tweetRepository->findBy([], ['creationTime' => 'DESC']);
        $selectedTweet = $tweetRepository->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTweet($selectedTweet);
            $comment->setDateTime(new \DateTime());
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès !');
            return $this->redirectToRoute('app_tweet_index');
        }

        $limit = 5;
        $page = max(1, (int) $request->query->get('page', 1));
        $offset = ($page - 1) * $limit;

        $totalTweets = count($tweetRepository->findAll());
        $tweets = $tweetRepository->findBy([], ['creationTime' => 'DESC'], $limit, $offset);

        return $this->render('tweet/index.html.twig', [
            'tweets' => $tweets,
            'selectedTweet' => $selectedTweet,
            'form' => $form->createView(),
            'currentPage' => $page,
            'totalPages' => ceil($totalTweets / $limit),
        ]);
    }


    // AJOUTER UN TWEET

    #[Route('/new', name: 'app_tweet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security, SluggerInterface $slugger): Response
    {
        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);
        $tweet->setCreationTime(new \DateTime());
        $tweet->setIdUser($security->getUser());

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($tweet);
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
                $media->setTweet($tweet);

                $entityManager->persist($media);
                $entityManager->flush();
                $tweet->addMedium($media);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/new.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }


    // VOIR LE DÉTAIL D'UN TWEET

    #[Route('/{id}', name: 'app_tweet_show', methods: ['GET'])]
    public function show(Tweet $tweet): Response
    {
        return $this->render('tweet/show.html.twig', [
            'tweet' => $tweet,
        ]);
    }


    // MODIFIER UN TWEET

    #[Route('/{id}/edit', name: 'app_tweet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tweet $tweet, EntityManagerInterface $entityManager, Security $security, SluggerInterface $slugger): Response
    {

        dump($tweet->getIdUser());

        if ($security->getUser() == $tweet->getIdUser()) {

            $form = $this->createForm(TweetType::class, $tweet);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->persist($tweet);
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
                    $media->setTweet($tweet);

                    $entityManager->persist($media);
                    $entityManager->flush();
                    $tweet->addMedium($media);
                }

                $entityManager->flush();

                return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('tweet/edit.html.twig', [
                'tweet' => $tweet,
                'form' => $form,
            ]);
        } else {
            $this->addFlash('danger', "Vous n'avez pas le droit de consulter ou de modifier cette page");
            return $this->redirectToRoute('app_tweet_index');
        }
    }


    // SUPPRIMER UN TWEET

    #[Route('/{id}', name: 'app_tweet_delete', methods: ['POST'])]
    public function delete(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tweet->getId(), $request->getPayload()->getString('_token'))) {
            if ($tweet->getOriginalTweet()) {
                $originalTweet = $tweet->getOriginalTweet();
                $originalTweet->decrementRetweetCount();
            }

            $entityManager->remove($tweet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }


    // SIGNALER UN TWEET

    #[Route('/{id}/signal', name: 'app_tweet_signalTweet', methods: ['POST'])]
    public function signalTweet(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {


        if ($this->isCsrfTokenValid('signalTweet' . $tweet->getId(), $request->getPayload()->getString('_token'))) {

            $tweet->setIsSignaled(true);
            $entityManager->persist($tweet);
            $entityManager->flush();

            $this->addFlash('success', 'Ce tweet a bien été signalé, il est en attente de modération !');
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }
    // RETWEET
    #[Route('/{id}/retweet', name: 'app_tweet_retweet', methods: ['POST'])]

    public function retweet(Tweet $tweet, EntityManagerInterface $entityManager, TweetRepository $tweetRepository, Security $security): Response
    {
        //  @var User $currentUser
        $currentUser = $security->getUser();
        $existingRetweet = $tweetRepository->findOneBy([
            'idUser' => $currentUser,
            'originalTweet' => $tweet,
        ]);

        if ($existingRetweet) {
            $this->addFlash('warning', 'Vous avez déjà retweeté ce message.');
            return $this->redirectToRoute('app_tweet_show', ['id' => $tweet->getId()]);
        }


        $newRetweet = new Tweet();
        $newRetweet->setIdUser($currentUser);
        $newRetweet->setContent($tweet->getContent());
        $newRetweet->setCreationTime(new \DateTime());
        $newRetweet->setOriginalTweet($tweet);
        $newRetweet->setRetweetCount(0);


        $entityManager->persist($newRetweet);


        $tweet->incrementRetweetCount();


        $entityManager->flush();

        $this->addFlash('success', 'Tweet retweeté avec succès !');

        return $this->redirectToRoute('app_tweet_show', ['id' => $tweet->getId()]);
    }
}
