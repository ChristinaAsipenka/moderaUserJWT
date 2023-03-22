<?php

namespace App\Controller;

use App\Service\FileService;
use App\Service\ReportService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class ReportController extends AbstractController
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    #[Route('/user/report', name: 'app_report_getreport',methods: 'GET')]
    #[OA\RequestBody(required: true, content: new JsonContent(example: '{"from":"2023-03-18 16:48:17", "till":"2023-03-18 16:48:17"'))]
    #[OA\Response(response: 200, description: 'Returns success "true"',content: new OA\JsonContent( example: "{'success':true}"))]
    #[OA\Tag(name: 'Report')]
    #[Security(name: 'Bearer')]
    public function getReport(Request $request, ReportService $reportService): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $from = (strlen($data['from']) !== 0 ? $data['from'] : null);
        $till = (strlen($data['till']) !== 0 ? $data['till'] : null);
        try {
            $res_array = $reportService->makeReport($from,$till);
            $message = [
                "success"=> true,
                "file" => "http://localhost:8080/". $this->fileService->createFile($res_array),
                "fileSecond" => $this->getParameter('kernel.project_dir'). $this->fileService->createFile($res_array)
            ];

            return new JsonResponse($message, Response::HTTP_OK);
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
    }
}
