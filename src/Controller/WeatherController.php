<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather', name: 'app_weather')]
    public function index(WeatherService $weatherService): Response
    {
        try {
            $weather = $weatherService->getWeather();
            $weather['main']['temp'] -= 273.15;
            $weather['main']['temp_max'] -= 273.15;
            $weather['main']['temp_min'] -= 273.15;
            $weather['main']['feels_like'] -= 273.15;
            $type = 'success';
            $msg = 'Weather successfully fetched';
        } catch (\Exception $e) {
            $type = 'danger';
            $msg = $e->getMessage();
        }
        $this->addFlash($type, $msg);
        return $this->render('weather/index.html.twig', [
            'controller_name' => 'WeatherController',
            'weather' => $weather
        ]);
    }
}
