<?php

namespace App\Service;

use App\DTO\NewUserDTO;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function createNewUser(NewUserDTO $newUserDTO)
    {
        if ($this->isUserExist($newUserDTO->getEmail()) === null) {

            $timestamp = new DateTimeImmutable(date('d.m.Y H:i:s'));
            $user = new User();
            $user->setName($newUserDTO->getName());
            $user->setEmail($newUserDTO->getEmail());
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $newUserDTO->getPassword()
            );
            $user->setPassword($hashedPassword);
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
