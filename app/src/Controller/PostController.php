<?php

namespace App\Controller;

use App\DTO\PostDTO;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    private PostService $postService;
    private SerializerInterface $serializer;

    public function __construct(PostService $postService,SerializerInterface $serializer)
    {
        $this->postService = $postService;
        $this->serializer = $serializer;
    }

    #[Route('/user/post', name:'app_post_createpost', methods:'POST')]
    public function createPost(Request $request): JsonResponse
    {
        $strForDTO = json_decode($request->getContent(), true);
        $resultDTO = $this->serializer->deserialize(json_encode($strForDTO), PostDTO::class, 'json');
        $message = $this->postService->create($resultDTO);
        $body = [
            "success"=>true,
            "message" => $message
        ];
        return new JsonResponse($body, Response::HTTP_OK);
    }

    #[Route('/user/post/{id}', name:'app_post_updatepost', methods:'PUT')]
    public function updatePost(int $id, Request $request)
    {
        $strForDTO = json_decode($request->getContent(), true);
        $strForDTO['id'] = $id;
        $resultDTO = $this->serializer->deserialize(json_encode($strForDTO), PostDTO::class, 'json');
        $message = $this->postService->update($resultDTO);
        $body = [
            "success"=>true,
            "message" => $message
        ];
        return new JsonResponse($body, Response::HTTP_OK);
    }

    #[Route('/user/post/{id}', name:'app_post_getpost', methods:'GET')]
    public function getPost(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $post = $this->postService->getPost($id);

        if ( $post !== null ){
            $message =[
                "success"=>true,
                "body"=>[
                    "title"=>$post->getTitle(),
                    "description" => $post -> getDescription(),
                    "created" => $post -> getCreatedAt(),
                    "updated" => $post -> getUpdatedAt()
                ]
            ];
        } else {
            $message =[
                "success"=>true,
                "message" => "Post does not exist"
            ];
        }

        return new JsonResponse($message, Response::HTTP_OK);
    }

    public function getPosts()
    {

    }

    #[Route('/user/post/{id}', name:'app_post_getpost', methods:'DELETE')]
    public function deletePost(int $id): JsonResponse
    {
        $message = $this->postService->deletePost($id);

        return new JsonResponse($message, Response::HTTP_OK);
    }
}
