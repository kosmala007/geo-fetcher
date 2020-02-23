<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Exception;

class HttpFailureStatusException extends \Exception
{
    public function __construct(int $status)
    {
        parent::__construct('Failture http status: '.$status);
    }
}
