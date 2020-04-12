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
 * @see https://developers.google.com/maps/documentation/geocoding/start
 */
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
            $url = self::ENDPOINT.'key='.$this->config->getApiKey().'&address='.urlencode($address);
            $response = $this->client->request('GET', $url);

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

            $url = self::ENDPOINT.'key='.$this->config->getApiKey().'&latlng='.$latLng['lat'].','.$latLng['lng'];
            $response = $this->client->request('GET', $url);

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

            $parts = $this->getAddressParts($content['results'][0]);
            $parts = array_merge($parts, $latLng);

            $result[] = $parts;
        }

        return $result;
    }

    public function getAddressParts(array $data): array
    {
        $result = [
            'country' => null,
            'administrative_area_level_1' => null,
            'locality' => null,
            'route' => null,
            'postal_code' => null,
            'street_number' => null,
        ];
        if (!is_array($data['address_components'])) {
            return $result;
        }
        foreach ($data['address_components'] as $component) {
            $types = $component['types'];
            if (in_array('street_number', $types)) {
                $result['street_number'] = $component['long_name'];
            } elseif (in_array('route', $types)) {
                $result['route'] = $component['long_name'];
            } elseif (in_array('locality', $types)) {
                $result['locality'] = $component['long_name'];
            } elseif (in_array('administrative_area_level_1', $types)) {
                $result['administrative_area_level_1'] = $component['long_name'];
            } elseif (in_array('country', $types)) {
                $result['country'] = $component['long_name'];
            } elseif (in_array('postal_code', $types)) {
                $result['postal_code'] = $component['long_name'];
            }
        }

        return $result;
    }
}
