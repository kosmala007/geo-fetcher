<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Adapter;

use DevPack\GeoFetcher\Config;

interface AdapterInterface
{
    public function getConfig(): ?Config;

    public function setConfig(Config $config);
}
