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

    public function testFetchAddreses()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'provider' => 'OpenStreetMaps',
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
