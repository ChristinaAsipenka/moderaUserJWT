<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserDataController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/user/{id}', name: 'user_data_one', methods: 'GET')]
    public function UserDataOne(int $id)
    {
        $data = $this->entityManager->getRepository(User::class)->find($id);

        if ($data !== null) {
            $response = [
                "success" => true,
                "body" => [
                    "id" => $data->getId(),
                    "email" => $data->getEmail(),
                    "name" => $data->getName(),
                    "roles" => $data->getRoles(),
                    "created at" => $data->getCreatedAt(),
                    "updated at" => $data->getUpdatedAt()
                ]
            ];
        } else {
            $response = ["success" => false];
        }
        return new JsonResponse($response, Response::HTTP_OK);
    }
}
