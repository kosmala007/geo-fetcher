<?php

declare(strict_types=1);

namespace DevPack\GedmoTreeRecalc\Tests;

use DevPack\GeoFetcher\Adapter\AdapterInterface;
use DevPack\GeoFetcher\Exception\AdapterApiFailureException;
use DevPack\GeoFetcher\Exception\InvalidLatLngArrayException;
use DevPack\GeoFetcher\Factory\AdapterFactory;
use DevPack\GeoFetcher\Factory\ConfigFactory;
use PHPUnit\Framework\TestCase;

class GoogleMapsAdapterTest extends TestCase
{
    public function testCreate()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'apiKey' => 'xyz',
            'provider' => 'GoogleMaps',
            'lang' => 'pl',
        ]);
        $adapter = AdapterFactory::create($config);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testFailedStatus()
    {
        $this->expectException(AdapterApiFailureException::class);

        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'apiKey' => 'fakeApieKey',
            'provider' => 'GoogleMaps',
            'lang' => 'pl',
        ]);
        $adapter = AdapterFactory::create($config);
        $adapter->fetchCoordinates([
            'Kielce Mickiewicza 1',
        ]);
    }

    public function testFetchCoordinates()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'apiKey' => $_ENV['GOOGLE_API_KEY'],
            'provider' => 'GoogleMaps',
            'lang' => 'pl',
        ]);
        $adapter = AdapterFactory::create($config);
        $result = $adapter->fetchCoordinates([
            'Kielce Mickiewicza 1',
        ]);

        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey('lat', $result[0]);
        $this->assertArrayHasKey('lng', $result[0]);
        $this->assertIsFloat($result[0]['lat']);
        $this->assertIsFloat($result[0]['lng']);
    }

    public function testFetchAddresesInvalidInput()
    {
        $this->expectException(InvalidLatLngArrayException::class);
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'apiKey' => $_ENV['GOOGLE_API_KEY'],
            'provider' => 'GoogleMaps',
            'lang' => 'pl',
        ]);
        $adapter = AdapterFactory::create($config);
        $adapter->fetchAddresses([
            [
                'lat' => null,
                'lng' => null,
            ]
        ]);
    }

    public function testFetchAddreses()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'apiKey' => $_ENV['GOOGLE_API_KEY'],
            'provider' => 'GoogleMaps',
            'lang' => 'pl',
        ]);
        $adapter = AdapterFactory::create($config);
        $result = $adapter->fetchAddresses([
            [
                'lat' => 50.869023,
                'lng' => 20.634476,
            ]
        ]);

        $expected = [
            'country' => 'Poland',
            'administrative_area_level_1' => 'świętokrzyskie',
            'locality' => 'Kielce',
            'route' => 'Henryka Sienkiewicza',
            'postal_code' => '25-354',
            'street_number' => '3',
            'lat' => 50.869023,
            'lng' => 20.634476,
        ];

        $this->assertEqualsCanonicalizing($expected, $result[0]);
    }
}
