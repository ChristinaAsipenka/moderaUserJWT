<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]

//#[ApiResource]

class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $rate = null;

    #[ORM\Column(length: 255)]
    private ?string $feedback_text = null;

    #[ORM\ManyToOne(inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $added_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getFeedbackText(): ?string
    {
        return $this->feedback_text;
    }

    public function setFeedbackText(string $feedback_text): self
    {
        $this->feedback_text = $feedback_text;

        return $this;
    }

    public function getPost(): ?post
    {
        return $this->post;
    }

    public function setPost(?post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->added_at;
    }

    public function setAddedAt(\DateTimeImmutable $added_at): self
    {
        $this->added_at = $added_at;

        return $this;
    }
}
