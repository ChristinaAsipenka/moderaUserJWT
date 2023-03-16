<?php

namespace App\Service;

use App\DTO\NewUserDTO;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createNewUser(NewUserDTO $newUserDTO)
    {
        if ($this->isUserExist($newUserDTO->email) === null) {

            $timestamp = new DateTimeImmutable(date('d.m.Y H:i:s'));
            $user = new User();
            $user->setName($newUserDTO->getName());
            $user->setEmail($newUserDTO->getEmail());
            $user->setPassword($newUserDTO->getPassword());
            $user->setCreatedAt($timestamp);
            $user->setRoles(["ROLE_USER"]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

        }
    }

    public function isUserExist(string $email)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(["email" =>$email]);
    }
}
