<?php

namespace App\Controller;

use App\DTO\FeedbackDTO;
use App\Service\FeedbackService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function addFeedback(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $strForDTO = json_decode($request->getContent(), true);
        $resultDTO = $this->serializer->deserialize(json_encode($strForDTO), FeedbackDTO::class, 'json');
        $message = $this->feedbackService->addFeedback($resultDTO);

        return new JsonResponse($message, Response::HTTP_OK);
    }
}
