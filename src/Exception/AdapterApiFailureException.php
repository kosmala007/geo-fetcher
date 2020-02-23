<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Exception;

class AdapterApiFailureException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct('Adapter API Failture exception. '.$message);
    }
}
