<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Exception;

class InvalidConfigArgsException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
