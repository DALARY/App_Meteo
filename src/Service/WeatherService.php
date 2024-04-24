<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpClient\HttpClient;

class WeatherService
{
    private $client;
    private $apiKey;
    private $cityName;

    public function __construct()
    {
        $this->client = HttpClient::create();
        $this->apiKey = getenv('WEATHER_API_KEY');
        if (empty($this->apiKey)) {
            throw new Exception("La clé API n'est pas défini !");
        }
    }

    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    /**
     * @return array
     */
    public function getWeather()
    {
        try {
            $response = $this->client->request('GET', 'https://api.openweathermap.org/data/2.5/weather?q='.$this->cityName.'&appid='.$this->apiKey.'&units=metric&lang=fr');
        
            if($response->getStatusCode() === 200) {
                return $response->toArray();
            } else {
                throw new Exception("Nom de ville invalide !");
            }
        } catch (Exception $error) {
            throw new Exception("Erreur : ". $error->getMessage());
        }
        
    }

    /**
     * @return array
     */
    public function getWeatherForecast()
    {
        try {
            $response = $this->client->request('GET', 'https://api.openweathermap.org/data/2.5/forecast?q='.$this->cityName.'&appid='.$this->apiKey.'&units=metric&lang=fr');
        
            if($response->getStatusCode() === 200) {
                return $response->toArray();
            } else {
                throw new Exception("Nom de ville invalide !");
            }
        } catch (Exception $error) {
            throw new Exception("Erreur : ". $error->getMessage());
        }
        
    }
}
