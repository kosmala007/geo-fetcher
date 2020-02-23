<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Adapter;

use DevPack\GeoFetcher\Config;
use DevPack\GeoFetcher\Exception\AdapterApiFailureException;
use GuzzleHttp\Client;
use DevPack\GeoFetcher\Exception\HttpFailureStatusException;

class GoogleMapsAdapter implements AdapterInterface
{
    use AdapterTrait;

    const ENDPOINT = 'https://maps.googleapis.com/maps/api/geocode/json?';

    private $config;
    private $client;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client();
    }

    public function fetchCoordinates(array $array): array
    {
        $result = [];
        foreach ($array as $address) {
            $response = $this->client->request('GET',
                self::ENDPOINT.'key='.$this->config->getApiKey().'&address='.urlencode($address)
            );

            if (200 != $response->getStatusCode()) {
                throw new HttpFailureStatusException($response->getStatusCode());
            }

            $content = json_decode($response->getBody()->getContents(), true);
            if ('OK' !== $content['status']) {
                throw new AdapterApiFailureException(
                    'Status: '.$content['status'].
                    'Error message: '.$content['error_message']
                );
            }
        }

        return $result;
    }

    public function fetchAddresses(array $array): array
    {
        $result = [];

        return $result;
    }
}
