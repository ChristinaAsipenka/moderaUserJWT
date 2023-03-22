<?php

namespace App\Entity;

use App\Repository\FeedbackTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedbackTypeRepository::class)]
class FeedbackType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $FeedbackType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeedbackType(): ?string
    {
        return $this->FeedbackType;
    }

    public function setFeedbackType(string $FeedbackType): self
    {
        $this->FeedbackType = $FeedbackType;

        return $this;
    }
}
