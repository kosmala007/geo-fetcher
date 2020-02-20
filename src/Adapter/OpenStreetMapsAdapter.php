<?php

namespace DevPack\GeoFetcher\Adapter;

use DevPack\GeoFetcher\Config;

class OpenStreetMapsAdapter implements AdapterInterface
{
    use AdapterTrait;

    const ENDPOINT = '';

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }
}
