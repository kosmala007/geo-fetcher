<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Exception;

class InvalidLatLngArrayException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Input array must have "lat" and "lng" keys.');
    }
}
