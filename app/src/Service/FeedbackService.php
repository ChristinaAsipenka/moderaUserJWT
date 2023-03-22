<?php

namespace App\Service;

use App\DTO\FeedbackDTO;
use App\Entity\Feedback;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FeedbackService
{
    private EntityManagerInterface $entityManager;
    private User $currentUser;

    public function __construct(EntityManagerInterface $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->currentUser = $userService->getCurrentUser();
    }

    public function addFeedback(FeedbackDTO $feedbackDTO): string
    {
        $timestamp = new \DateTimeImmutable(date('d.m.Y H:i:s'));
        $post = $this->entityManager->getRepository(Post::class)->find($feedbackDTO->getPost());
        $newFeedback = new Feedback();
        $newFeedback -> setOwner($this->currentUser);
        $newFeedback -> setFeedbackText( $feedbackDTO->getFeedbackText() );
        $newFeedback -> setPost($post);
        $newFeedback -> setRate( $feedbackDTO->getRate() );
        $newFeedback -> setAddedAt($timestamp);
        $this->entityManager->persist($newFeedback);
        $this->entityManager->flush();

        return "Feedback is added";
    }
}
