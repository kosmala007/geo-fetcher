<?php

declare(strict_types=1);

namespace DevPack\GedmoTreeRecalc\Tests;

use DevPack\GeoFetcher\Adapter\AdapterInterface;
use DevPack\GeoFetcher\Exception\AdapterApiFailureException;
use DevPack\GeoFetcher\Factory\AdapterFactory;
use DevPack\GeoFetcher\Factory\ConfigFactory;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase
{
    public function testGoogleMapsAdapterCreate()
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

    public function testOpenStreetMapsAdapterCreate()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'apiKey' => 'xyz',
            'provider' => 'OpenStreetMaps',
            'lang' => 'pl',
        ]);
        $adapter = AdapterFactory::create($config);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testGoogleMapsAdapterFailedStatus()
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

    public function testGoogleMapsAdapterFetchCoordinates()
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
}
