<?php

declare(strict_types=1);

namespace DevPack\GedmoTreeRecalc\Tests;

use DevPack\GeoFetcher\Adapter\AdapterInterface;
use DevPack\GeoFetcher\Exception\InvalidLatLngArrayException;
use DevPack\GeoFetcher\Factory\AdapterFactory;
use DevPack\GeoFetcher\Factory\ConfigFactory;
use PHPUnit\Framework\TestCase;

class OpenStreetMapsAdapterTest extends TestCase
{
    public function testAdapterCreate()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'provider' => 'OpenStreetMaps',
            'lang' => 'pl',
        ]);
        $adapter = AdapterFactory::create($config);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testFetchCoordinates()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'provider' => 'OpenStreetMaps',
            'lang' => 'pl',
        ]);
        $adapter = AdapterFactory::create($config);
        $result = $adapter->fetchCoordinates([
            'Kielce, Mickiewicza 1',
        ]);

        $this->assertArrayHasKey('lat', $result);
        $this->assertArrayHasKey('lng', $result);
        $this->assertIsFloat($result['lat']);
        $this->assertIsFloat($result['lng']);
    }

    public function testFetchAddresesInvalidInput()
    {
        $this->expectException(InvalidLatLngArrayException::class);
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'provider' => 'OpenStreetMaps',
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
}
