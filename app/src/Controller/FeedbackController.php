<?php

namespace App\Controller;

use App\DTO\FeedbackDTO;
use App\Service\FeedbackService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FeedbackController extends AbstractController
{
    private FeedbackService $feedbackService;
    private SerializerInterface $serializer;

    public function __construct(FeedbackService $feedbackService,SerializerInterface $serializer)
    {
        $this->feedbackService = $feedbackService;
        $this->serializer = $serializer;
    }
    #[Route('/user/post/feedback', name: 'app_feedback_addfeedback', methods: 'POST')]
    #[OA\RequestBody(required: true, content: new JsonContent(example: '{"post":"post id", "feedback_text":"How I like it|Will I recommend this post to my friends", "rates": 5}'))]
    #[OA\Response(response: 200, description: 'Returns success "true"',content: new OA\JsonContent( example: "{'success':true}"))]
    #[OA\Tag(name: 'Feedback')]
    #[Security(name: 'Bearer')]
    public function addFeedback(Request $request): JsonResponse
    {
        $strForDTO = json_decode($request->getContent(), true);
        $resultDTO = $this->serializer->deserialize(json_encode($strForDTO), FeedbackDTO::class, 'json');
        $message = $this->feedbackService->addFeedback($resultDTO);

        return new JsonResponse($message, Response::HTTP_OK);
    }
}
