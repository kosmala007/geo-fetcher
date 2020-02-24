<?php

declare(strict_types=1);

namespace DevPack\GedmoTreeRecalc\Tests;

use DevPack\GeoFetcher\Adapter\AdapterInterface;
use DevPack\GeoFetcher\Factory\AdapterFactory;
use DevPack\GeoFetcher\Factory\ConfigFactory;
use PHPUnit\Framework\TestCase;

class OpenStreetMapsAdapterTest extends TestCase
{
    public function testAdapterCreate()
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
}
