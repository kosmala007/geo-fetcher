<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Factory;

use DevPack\GeoFetcher\Adapter\AdapterInterface;
use DevPack\GeoFetcher\Adapter\GoogleMapsAdapter;
use DevPack\GeoFetcher\Adapter\OpenStreetMapsAdapter;
use DevPack\GeoFetcher\Config;

class AdapterFactory
{
    public static function create(Config $config): AdapterInterface
    {
        if ('GoogleMaps' == $config->getProvider()) {
            $adapter = new GoogleMapsAdapter($config);
        } elseif ('OpenStreetMaps' == $config->getProvider()) {
            $adapter = new OpenStreetMapsAdapter($config);
        }

        return $adapter;
    }
}
