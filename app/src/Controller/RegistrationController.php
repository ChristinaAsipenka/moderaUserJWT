<?php

namespace App\Controller;

use App\DTO\NewUserDTO;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
    public function newUserRegistration(Request $request){
        $strForDTO = json_decode($request->getContent(), true);
        $resultDTO = $this->serializer->deserialize(json_encode($strForDTO), NewUserDTO::class, 'json');
        $this->registrationService->createNewUser($resultDTO);
    }
}
