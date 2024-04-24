<?php

namespace App\Controller;

use App\Form\WeatherFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WeatherService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WeatherController extends AbstractController
{
    private $weatherService;

    public function __construct(WeatherService $weather)
    {
        $this->weatherService = $weather;
    }

    /**
     * @Route("/weather", name="weather")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(WeatherFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cityName = $form->get('city_name')->getData();
            if ($cityName == "") {
                $cityName = "Toulouse";
            }
            $this->weatherService->setCityName($cityName);
            $weatherData = $this->weatherService->getWeather();
        } else {
            $this->weatherService->setCityName('Toulouse');
            $weatherData = $this->weatherService->getWeather();
        }

        return $this->render('weather/index.html.twig', array(
            'weatherForm' => $form->createView(),
            'weatherData' => $weatherData,
        ));
    }

    /**
     * @Route("/weather/forecast/{cityName}", name="weather_forecast")
     */
    public function forecast($cityName)
    {
        try {
            if ($cityName == "") {
                $cityName = "Toulouse";
            }
            $this->weatherService->setCityName($cityName);
            $weatherData = $this->weatherService->getWeatherForecast();
            return new JsonResponse($weatherData);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
