<?php

namespace App\Controller;


use OpenApi\Attributes as OA;

use App\DTO\NewUserDTO;
use App\Service\RegistrationService;
use OpenApi\Attributes\JsonContent;
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

    /**

     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/registration', name:'app_registration_newuserregistration', methods:'POST')]
    #[OA\Response(response: 200,description: 'Returns success "true"',content: new OA\JsonContent( example: "{'success':true}"))]
    #[OA\RequestBody(required: true, content: new JsonContent(example: '{"email":"user@example.com", "password":"123456", "name": "Sherlock Holmes"}'))]
    #[OA\Tag(name: 'registration')]
    /*
     * @param Request $request ('email', 'password', 'name')
     * @return JsonResponse
     */
    #[Route('/register', name:'register', methods:'POST')]
    public function newUserRegistration(Request $request): JsonResponse
    {
        $strForDTO = json_decode($request->getContent(), true);
        $resultDTO = $this->serializer->deserialize(json_encode($strForDTO), NewUserDTO::class, 'json');
        try {
            $this->registrationService->createNewUser($resultDTO);
        }catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'success' => false,
                    'body' => [
                        'message' => $exception->getMessage(),
                    ],
                ],
                $exception->getCode());
        }


        return new JsonResponse(["success"=>true], Response::HTTP_OK);
    }
}
