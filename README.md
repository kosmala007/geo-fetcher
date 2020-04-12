# Geo Fetcher

Library for retrieving coordinates for addresses and vice versa

GeoFetcher has two providers implemented:

* [GoogleMaps](https://developers.google.com/maps/documentation/geocoding/start)
* [OpenStreetMap](https://nominatim.org/release-docs/latest/)

## How it use?

### Instalation

```
composer install ...
```

### Initialize GeoFetcher

#### Available providers

* GoogleMaps
* OpenStreetMaps

```php
<?php

use DevPack\GeoFetcher\GeoFetcher;

$geoFetcher = new GeoFetcher([
    'apiKey' => 'yourApiKey',     // required if You use GoogleMaps
    'provider' => 'GoogleMaps',   // one from available providers
    'lang' => 'pl',               // code ISO 639-1
]);
```

### Fetch Coordinates from addres string

Paramter is array of addreses and GeoFetcher always return array

```php
// Feth coordinates from adress - parameter must be array
$result = $geoFetcher->fetchCoordinates(
    ['Kielce, Mickiewicza 1'],
);
```

#### Example response

```php
array:1 [
    0 => array:2 [
        "lat" => 50.8676012
        "lng" => 20.6329186
    ]
]
```

### Fetch address details from coordinates

Paramter is array of addreses and GeoFetcher always return array

```php
// Fetch address from coordinates - parameter must be array
$result = $geoFetcher->fetchAddresses([
    [
        'lat' => 50.869023,
        'lng' => 20.634476,
    ],
]);
```

#### Example response

```php
array:1 [
    0 => array:8 [
        "country" => "Polska"
        "administrative_area_level_1" => "województwo świętokrzyskie"
        "locality" => "Kielce"
        "route" => "Henryka Sienkiewicza"
        "postal_code" => "25-350"
        "street_number" => "3"
        "lat" => 50.869023
        "lng" => 20.634476
    ]
]
```
