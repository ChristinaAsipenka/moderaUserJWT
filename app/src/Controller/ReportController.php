<?php

namespace App\Controller;

use App\Service\FileService;
use App\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    #[Route('/user/report', name: 'app_report_getreport',methods: 'GET')]
    public function getReport(Request $request, ReportService $reportService): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $from = (strlen($data['from']) !== 0 ? $data['from'] : null);
        $till = (strlen($data['till']) !== 0 ? $data['till'] : null);
        $res_array = $reportService->makeReport($from,$till);
        $file = $this->fileService->createFile($res_array);

        $message = [
            "success"=> true,
            "file" => $file
        ];

        return new JsonResponse($message, Response::HTTP_OK);
    }
}
