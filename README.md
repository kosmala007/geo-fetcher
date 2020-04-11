# Geo Fetcher

Library for retrieving coordinates for addresses and vice versa

GeoFetcher has two providers implemented:

* [GoogleMaps](https://developers.google.com/maps/documentation/geocoding/start)
* [OpenStreetMap](https://nominatim.org/release-docs/latest/)

## How it use?

1. Install by composer
2. Create instance GeoFetcher and use it

```php
<?php

use DevPack\GeoFetcher\GeoFetcher;

$geoFetcher = new GeoFetcher([
    'apiKey' => 'yourApiKey',
    'provider' => 'GoogleMaps',
    'lang' => 'pl',
]);

// Feth coordinates from adress - parameter must be array
$result = $geoFetcher->fetchCoordinates(
    ['Kielce, Mickiewicza 1'],
);

// Fetch address from coordinates - parameter must be array
$result = $geoFetcher->fetchAddresses([
    [
        'lat' => 50.869023,
        'lng' => 20.634476,
    ],
]);

```
