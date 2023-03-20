<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherController extends AbstractController
{
    private const X_RAPID_API_KEY = "23d8835674mshc437a941587e974p1eb586jsn1bd9920f697";
    private const X_RAPID_API_HOST = "open-weather13.p.rapidapi.com";
    private const WEATHER_URL = "https://open-weather13.p.rapidapi.com/city/";

    /**
     * @param Request $request = {"city":"any_city"}
     * @param HttpClientInterface $client
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/weather', name: 'app_weather_getforecast', methods: 'GET')]
    public function getForecast(Request $request, HttpClientInterface $client): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $header = ["X-RapidAPI-Key" => self::X_RAPID_API_KEY, "X-RapidAPI-Host" => self::X_RAPID_API_HOST];
        $base_url = self::WEATHER_URL . $data['city'];

        try {
            $response = $client->request('GET', $base_url, ['headers' => $header]);
            if (200 !== $response->getStatusCode()) {
                throw new \Exception($response->getContent());
            }
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'success' => false,
                    'body' => [
                        'exception' => get_class($exception),
                        'message' => $exception->getMessage(),
                        'status' => $exception->getCode(),
                        'line' => $exception->getLine(),
                        'file' => $exception->getFile(),
                    ],
                ],
                $exception->getCode());
        }
        return new JsonResponse(json_decode($response->getContent()), $response->getStatusCode());
    }
}
