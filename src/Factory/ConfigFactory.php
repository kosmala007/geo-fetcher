<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Factory;

use DevPack\GeoFetcher\Config;
use DevPack\GeoFetcher\Exception\InvalidConfigArgsException;
use DevPack\GeoFetcher\Utility\LangIso;

class ConfigFactory
{
    public function create(array $args): Config
    {
        if (empty($args['apiKey']) && 'GoogleMaps' == $args['provider']) {
            throw new InvalidConfigArgsException('Missing API key.');
        }
        if (empty($args['provider'])) {
            throw new InvalidConfigArgsException('Missing provider.');
        }
        if (!in_array($args['provider'], Config::PROVIDERS)) {
            throw new InvalidConfigArgsException('Unrecognized provider.');
        }
        if (!empty($args['lang']) && !LangIso::isExist($args['lang'])) {
            throw new InvalidConfigArgsException(
                'Unrecognized lang iso code. Use ISO 639-1 standard.'
            );
        }

        if (!isset($args['lang'])) {
            $args['lang'] = LangIso::DEF_CODE;
        }
        $args['apiKey'] = $args['apiKey'] ?? null;

        $config = new Config();
        $config
            ->setApiKey($args['apiKey'])
            ->setProvider($args['provider'])
            ->setLang($args['lang']);

        return $config;
    }
}
