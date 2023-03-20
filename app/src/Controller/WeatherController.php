<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherController extends AbstractController
{
    #[Route('/weather', name: 'app_weather_getforecast',methods: 'GET')]
    public function getForecast(Request $request, HttpClientInterface $client): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $header = ["X-RapidAPI-Key" => "23d8835674mshc437a941587e974p1eb586jsn1bd9920f6970", "X-RapidAPI-Host"=>"open-weather13.p.rapidapi.com"];
        $base_url = "https://open-weather13.p.rapidapi.com/city/".$data['city'];
        $response =$client->request('GET',$base_url,['headers'=>$header]);

        return new JsonResponse(json_decode($response->getContent()), $response->getStatusCode());
    }
}
