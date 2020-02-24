<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Adapter;

use DevPack\GeoFetcher\Config;
use DevPack\GeoFetcher\Exception\AdapterApiFailureException;
use DevPack\GeoFetcher\Exception\HttpFailureStatusException;
use DevPack\GeoFetcher\Exception\InvalidLatLngArrayException;
use GuzzleHttp\Client;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GoogleMapsAdapter implements AdapterInterface
{
    use AdapterTrait;

    const ENDPOINT = 'https://maps.googleapis.com/maps/api/geocode/json?';
    const PROPERTY_PATH = '[results][0][geometry][location]';

    private $config;
    private $client;
    private $propertyAccessor;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client();
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function fetchCoordinates(array $array): array
    {
        $result = [];
        foreach ($array as $address) {
            $response = $this->client->request('GET',
                self::ENDPOINT.'key='.$this->config->getApiKey().
                '&address='.urlencode($address)
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

            $latLng = $this->propertyAccessor->getValue($content, self::PROPERTY_PATH);
            $result[] = $latLng;
        }

        return $result;
    }

    public function fetchAddresses(array $array): array
    {
        $result = [];
        foreach ($array as $latLng) {
            if (empty($latLng['lat']) || empty($latLng['lng'])) {
                throw new InvalidLatLngArrayException();
            }
        }

        return $result;
    }
}
