<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;

class UserDataController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/user/{id}', name: 'user_data_one', methods: 'GET')]
    #[OA\Response(response: 200, description: 'Returns Users info', content: new OA\JsonContent(example: ' "success": true,
                  "body": {
                    "id": 2,
                    "email": "test2@test.com",
                    "name": "test2",
                    "roles": [
                      "ROLE_USER"
                    ],
                    "created at": {
                      "date": "2023-03-16 22:07:56.000000",
                      "timezone_type": 3,
                      "timezone": "UTC"
                    },
                    "updated at": null
                  }'))]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'user')]
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
