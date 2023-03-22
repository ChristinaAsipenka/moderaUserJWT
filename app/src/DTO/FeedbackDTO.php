<?php

namespace App\DTO;

use App\Entity\Post;
use App\Entity\User;

class FeedbackDTO
{
    private ?int $id = null;

    private ?User $owner = null;

    private ?int $rate = null;

    private ?string $feedback_text = null;

    private ?int $post= null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return int
     */
    public function getRate(): int
    {
        return $this->rate;
    }

    /**
     * @param int $rate
     */
    public function setRate(int $rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function getFeedbackText(): string
    {
        return $this->feedback_text;
    }

    /**
     * @param string $feedback_text
     */
    public function setFeedbackText(string $feedback_text): void
    {
        $this->feedback_text = $feedback_text;
    }

    /**
     * @return integer
     */
    public function getPost(): int
    {
        return $this->post;
    }

    /**
     * @param integer $post
     */
    public function setPost(int $post): void
    {
        $this->post = $post;
    }
}
