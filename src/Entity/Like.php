<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'user_tweet_unique', columns: ['user_id', 'tweet_id'])
])]
#[UniqueEntity(
    fields: ['user', 'tweet'],
)]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Tweet $tweet = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Comment $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTweet(): ?Tweet
    {
        return $this->tweet;
    }

    public function setTweet(?Tweet $tweet): static
    {
        $this->tweet = $tweet;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
