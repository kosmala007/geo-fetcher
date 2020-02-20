<?php

namespace DevPack\GeoFetcher\Factory;

use DevPack\GeoFetcher\Config;
use DevPack\GeoFetcher\Exception\InvalidConfigArgsException;
use DevPack\GeoFetcher\Utility\LangIso;

class ConfigFactory
{
    const DEF_LANG = 'en';

    private $args = [];

    public function create(array $args): Config
    {
        $this->args = $args;
        $this
            ->validate()
            ->prepare();

        $config = new Config();
        $config
            ->setApiKey($args['apiKey'])
            ->setProvider($args['provider'])
            ->setLang($args['lang']);

        return $config;
    }

    public function validate(): self
    {
        if (empty($this->args['apiKey'])) {
            throw new InvalidConfigArgsException('Missing API key.');
        }
        if (empty($this->args['provider'])) {
            throw new InvalidConfigArgsException('Missing provider.');
        }
        if (!in_array($this->args['provider'], Config::PROVIDERS)) {
            throw new InvalidConfigArgsException('Unrecognized provider.');
        }
        if (!empty($this->args['lang'])
            && !LangIso::isExist($this->args['lang'])
        ) {
            throw new InvalidConfigArgsException(
                'Unrecognized lang iso code. Use ISO 639-1 standard.'
            );
        }

        return $this;
    }

    public function prepare(): self
    {
        if (!isset($this->args['lang'])) {
            $this->args['lang'] = LangIso::DEF_CODE;
        }

        return $this;
    }
}
