<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
class Tweet
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTime $creationTime = null;

    #[ORM\ManyToOne(inversedBy: 'tweets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[Assert\NotBlank(message: 'Vous devez écrire du texte')]
    #[Assert\Length(
        min: 1,
        max: 280,
        minMessage: 'Le champ doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le champ ne peut pas dépasser {{ limit }} caractères',
        normalizer: 'trim'
    )]

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'tweet', cascade: ['persist', 'remove'])]
    private Collection $media;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'tweet', cascade: ['persist', 'remove'])]
    private Collection $comment;

    #[ORM\Column]
    private ?bool $isSignaled = false;

    /**
     * @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'tweet')]
    private Collection $likes;


    #[ORM\ManyToOne(targetEntity: self::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $originalTweet = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $retweetCount = 0;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreationTime(): ?\DateTime
    {
        return $this->creationTime;
    }

    public function setCreationTime(\DateTime $creationTime): static
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setTweet($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getTweet() === $this) {
                $medium->setTweet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setTweet($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTweet() === $this) {
                $comment->setTweet(null);
            }
        }

        return $this;
    }

    public function isSignaled(): ?bool
    {
        return $this->isSignaled;
    }

    public function setIsSignaled(bool $isSignaled): static
    {
        $this->isSignaled = $isSignaled;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setTweet($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getTweet() === $this) {
                $like->setTweet(null);
            }
        }

        return $this;
    }

    public function getOriginalTweet(): ?self
    {
        return $this->originalTweet;
    }

    public function setOriginalTweet(?self $originalTweet): static
    {
        $this->originalTweet = $originalTweet;
        return $this;
    }

    public function getRetweetCount(): int
    {
        return $this->retweetCount;
    }

    public function setRetweetCount(int $retweetCount): static
    {
        $this->retweetCount = $retweetCount;
        return $this;
    }

    public function incrementRetweetCount(): static
    {
        $this->retweetCount++;
        return $this;
    }

    public function decrementRetweetCount(): static
    {
        if ($this->retweetCount > 0) {
            $this->retweetCount--;
        }
        return $this;
    }
}
