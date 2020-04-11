<?php

declare(strict_types=1);

namespace DevPack\GeoFetcher\Tests;

use PHPUnit\Framework\TestCase;

class PhpunitConfigTest extends TestCase
{
    public function testhasGoogleApiKey()
    {
        $this->assertArrayHasKey('GOOGLE_API_KEY', $_ENV);
    }
}
