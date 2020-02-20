<?php

namespace DevPack\GeoFetcher;

use DevPack\GeoFetcher\Factory\AdapterFactory;
use DevPack\GeoFetcher\Factory\ConfigFactory;

class GeoFetcher
{
    private $adapter;

    public function __construct(array $args)
    {
        $factory = new ConfigFactory();
        $config = $factory->create($args);

        $this->adapter = AdapterFactory::create($config);
    }

    public function fetchCoordinates(array $input)
    {
        # code...
    }

    public function fetchAddresses(array $input)
    {
        # code...
    }
}
