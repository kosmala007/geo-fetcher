<?php

declare(strict_types=1);

namespace DevPack\GedmoTreeRecalc\Tests;

use DevPack\GeoFetcher\Config;
use DevPack\GeoFetcher\Exception\InvalidConfigArgsException;
use DevPack\GeoFetcher\Factory\ConfigFactory;
use DevPack\GeoFetcher\GeoFetcher;
use PHPUnit\Framework\TestCase;

class ConfigFactoryTest extends TestCase
{
    public function testMissingProvider()
    {
        $this->expectException(InvalidConfigArgsException::class);

        $configFactory = new ConfigFactory();
        $configFactory->create([
            'apiKey' => 'xyz',
        ]);
    }

    public function testMissingApiKey()
    {
        $this->expectException(InvalidConfigArgsException::class);

        $configFactory = new ConfigFactory();
        $configFactory->create([
            'provider' => 'GoogleMaps',
        ]);
    }

    public function testWrongProvider()
    {
        $this->expectException(InvalidConfigArgsException::class);

        $configFactory = new ConfigFactory();
        $configFactory->create([
            'apiKey' => 'xyz',
            'provider' => 'anything',
        ]);
    }

    public function testWrongLangCode()
    {
        $this->expectException(InvalidConfigArgsException::class);

        $configFactory = new ConfigFactory();
        $configFactory->create([
            'apiKey' => 'xyz',
            'provider' => 'GoogleMaps',
            'lang' => 'oo',
        ]);
    }

    public function testConfigCreate()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->create([
            'apiKey' => 'xyz',
            'provider' => 'GoogleMaps',
            'lang' => 'pl',
        ]);

        $this->assertInstanceOf(Config::class, $config);
    }
}
