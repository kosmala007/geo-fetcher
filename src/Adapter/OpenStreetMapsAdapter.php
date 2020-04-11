<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Adapter;

use DevPack\GeoFetcher\Config;
use DevPack\GeoFetcher\Exception\AdapterApiFailureException;
use DevPack\GeoFetcher\Exception\HttpFailureStatusException;
use DevPack\GeoFetcher\Exception\InvalidLatLngArrayException;
use GuzzleHttp\Client;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @see https://nominatim.org/release-docs/latest/
 */
class OpenStreetMapsAdapter implements AdapterInterface
{
    use AdapterTrait;

    const ENDPOINT = 'https://nominatim.openstreetmap.org/search?format=json&';

    private $config;
    private $client;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'headers' => [
                'Accept-Language' => $config->getLang(),
            ],
        ]);
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function fetchCoordinates(array $array): array
    {
        $result = [];
        foreach ($array as $address) {
            $response = $this->client->request('GET',
                self::ENDPOINT.'q='.urlencode($address)
            );

            if (200 != $response->getStatusCode()) {
                throw new HttpFailureStatusException($response->getStatusCode());
            }

            $content = json_decode($response->getBody()->getContents(), true);
            $lat = (float) $this->propertyAccessor->getValue($content, '[0][lat]');
            $lng = (float) $this->propertyAccessor->getValue($content, '[0][lon]');
            if (empty($lat) || empty($lng)) {
                throw new AdapterApiFailureException('No in response lat or lng');
            }

            $result = [
                'lat' => $lat,
                'lng' => $lng,
            ];
        }

        return $result;
    }

    public function fetchAddresses(array $array): array
    {
        $result = [];

        return $result;
    }
}
