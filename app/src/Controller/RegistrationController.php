<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiProperty;
use App\DTO\NewUserDTO;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
#[AsController]
class RegistrationController extends AbstractController
{
    private SerializerInterface $serializer;
    private RegistrationService $registrationService;


    public function __construct(SerializerInterface $serializer, RegistrationService $registrationService)
    {
        $this->serializer = $serializer;
        $this->registrationService = $registrationService;
    }

    #[Route('/register', name:'register', methods:'POST')]
    public function newUserRegistration(Request $request): JsonResponse
    {
        $strForDTO = json_decode($request->getContent(), true);
        $resultDTO = $this->serializer->deserialize(json_encode($strForDTO), NewUserDTO::class, 'json');
        $this->registrationService->createNewUser($resultDTO);

        return new JsonResponse(["success"=>true], Response::HTTP_OK);
    }
}
