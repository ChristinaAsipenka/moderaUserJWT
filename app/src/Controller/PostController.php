<?php

namespace App\Controller;

use App\DTO\PostDTO;
use App\Service\PostService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/user/post', name:'app_post_createpost', methods:'POST')]
    #[OA\RequestBody(required: true, content: new JsonContent(example: '{"title":"title example", "description":"content"}'))]
    #[OA\Response(response: 200, description: 'Returns success "true"',content: new OA\JsonContent( example: '{"success":true, "message": "post created"}'))]
    #[OA\Tag(name: 'Post')]
    #[Security(name: 'Bearer')]
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
    #[OA\RequestBody(required: true, content: new JsonContent(example: '{"title":"title example", "description":"content"}'))]
    #[OA\Response(response: 200, description: 'Returns success "true"',content: new OA\JsonContent( example: '{"success":true, "body": {
                    "title": "test",
                    "description": "test test etst test",
                    "created": {
                      "date": "2023-03-18 16:45:49.000000",
                      "timezone_type": 3,
                      "timezone": "UTC"
                    },
                    "updated": null
                  }}'))]
    #[OA\Tag(name: 'Post')]
    #[Security(name: 'Bearer')]
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
    #[OA\Response(response: 200, description: 'Returns success "true"',content: new OA\JsonContent( example: '{"success":true}'))]
    #[OA\Tag(name: 'Post')]
    #[Security(name: 'Bearer')]
    public function getPost(int $id): JsonResponse
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

    #[Route('/user/post/{id}', name:'app_post_deletepost', methods:'DELETE')]
    #[OA\Response(response: 200, description: 'Your post was deleted"',content: new OA\JsonContent( example: "{'success':true}"))]
    #[OA\Tag(name: 'Post')]
    #[Security(name: 'Bearer')]
    public function deletePost(int $id): JsonResponse
    {
        $message = $this->postService->deletePost($id);

        return new JsonResponse($message, Response::HTTP_OK);
    }
}
