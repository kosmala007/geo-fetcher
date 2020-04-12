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

    const ENDPOINT_SEARCH = 'https://nominatim.openstreetmap.org/search?format=json&';
    const ENDPOINT_REVERSE = 'https://nominatim.openstreetmap.org/reverse?format=json&';

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
            $url = self::ENDPOINT_SEARCH.'q='.urlencode($address);
            $response = $this->client->request('GET', $url);

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
        foreach ($array as $latLng) {
            if (empty($latLng['lat']) || empty($latLng['lng'])) {
                throw new InvalidLatLngArrayException();
            }

            $url = self::ENDPOINT_REVERSE.'lat='.$latLng['lat'].'&lon='.$latLng['lng'];
            $response = $this->client->request('GET', $url);

            if (200 != $response->getStatusCode()) {
                throw new HttpFailureStatusException($response->getStatusCode());
            }

            $content = json_decode($response->getBody()->getContents(), true);

            $parts = $this->getAddressParts($content);
            $parts = array_merge($parts, $latLng);

            $result[] = $parts;
        }

        return $result;
    }

    public function getAddressParts(array $data): array
    {
        $result = [
            'country' => $this->propertyAccessor->getValue($data, '[address][country]'),
            'administrative_area_level_1' => $this->propertyAccessor->getValue($data, '[address][state]'),
            'locality' => $this->propertyAccessor->getValue($data, '[address][city]'),
            'route' => $this->propertyAccessor->getValue($data, '[address][pedestrian]'),
            'postal_code' => $this->propertyAccessor->getValue($data, '[address][postcode]'),
            'street_number' => $this->propertyAccessor->getValue($data, '[address][house_number]'),
        ];

        return $result;
    }
}
