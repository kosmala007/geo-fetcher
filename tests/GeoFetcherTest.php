<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Tests;

use DevPack\GeoFetcher\Factory\ConfigFactory;
use DevPack\GeoFetcher\GeoFetcher;
use PHPUnit\Framework\TestCase;

class ConfigFactoryTest extends TestCase
{
    public function testFetchCoordinates()
    {
        $geoFetcher = new GeoFetcher([
            'apiKey' => $_ENV['GOOGLE_API_KEY'],
            'provider' => 'GoogleMaps',
            'lang' => 'pl',
        ]);

        $result = $geoFetcher->fetchCoordinates([
            'Kielce, Mickiewicza 1',
        ]);

        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey('lat', $result[0]);
        $this->assertArrayHasKey('lng', $result[0]);
        $this->assertIsFloat($result[0]['lat']);
        $this->assertIsFloat($result[0]['lng']);
    }

    public function testFetchAddreses()
    {
        $geoFetcher = new GeoFetcher([
            'provider' => 'OpenStreetMaps',
            'lang' => 'pl',
        ]);

        $result = $geoFetcher->fetchAddresses([
            [
                'lat' => 50.869023,
                'lng' => 20.634476,
            ]
        ]);

        $expected = [
            'country' => 'Polska',
            'administrative_area_level_1' => 'województwo świętokrzyskie',
            'locality' => 'Kielce',
            'route' => 'Henryka Sienkiewicza',
            'postal_code' => '25-350',
            'street_number' => '3',
            'lat' => 50.869023,
            'lng' => 20.634476,
        ];
        $this->assertEqualsCanonicalizing($expected, $result[0]);
    }
}
