<?php

namespace App\Service;

use App\DTO\PostDTO;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    private EntityManagerInterface $entityManager;
    private User $currentUser;

    public function __construct(EntityManagerInterface $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->currentUser = $userService->getCurrentUser();
    }

    public function create(PostDTO $postDTO): string
    {
        $timestamp = new \DateTimeImmutable(date('d.m.Y H:i:s'));
        $newPost = new Post();
        $newPost->setTitle($postDTO->getTitle());
        $newPost->setDescription($postDTO->getDescription());
        $newPost->setCreatedAt($timestamp);
        $newPost->setOwner($this->currentUser);
        $this->entityManager->persist($newPost);
        $this->entityManager->flush();

        return "post created";
    }

    public function update(PostDTO $postDTO): string
    {
        $postUpd = $this->entityManager->getRepository(Post::class)->find($postDTO->getId());
        if ($postUpd !== null) {
            if ($this->currentUser === $postUpd->getOwner() || in_array("ROLE_ADMIN", $this->currentUser->getRoles())) {
                $timestamp = new \DateTimeImmutable(date('d.m.Y H:i:s'));
                $postUpd->setTitle($postDTO->getTitle());
                $postUpd->setDescription($postDTO->getDescription());
                $postUpd->setUpdatedAt($timestamp);
                $this->entityManager->persist($postUpd);
                $this->entityManager->flush();

                return "Your post updated";
            }
            return "You can't edit this post";
        }
        return "Your post not found";
    }

    public function getPost(int $id)
    {
        return $this->entityManager->getRepository(Post::class)->find($id);
    }

    public function deletePost(int $id)
    {
        $postDel = $this->entityManager->getRepository(Post::class)->find($id);
        if ($this->currentUser === $postDel->getOwner() || in_array("ROLE_ADMIN", $this->currentUser->getRoles())) {
            $this->entityManager->remove($postDel);
            $this->entityManager->flush();

            return "Your post was deleted";
        } else {
            return "Your cannot delete this post";
        }


    }
}
