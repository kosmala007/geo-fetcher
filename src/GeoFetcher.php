<?php

declare(strict_types=1);

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

    public function fetchCoordinates(array $input): array
    {
        $result = $this->adapter->fetchCoordinates($input);

        return $result;
    }

    public function fetchAddresses(array $input): array
    {
        $result = $this->adapter->fetchAddresses($input);

        return $result;
    }
}
