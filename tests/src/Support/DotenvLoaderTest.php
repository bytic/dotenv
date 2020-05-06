<?php

namespace ByTIC\Dotenv\Tests\Support;

use ByTIC\Dotenv\Support\DotenvLoader;
use ByTIC\Dotenv\Tests\AbstractTest;
use ByTIC\Dotenv\Tests\Fixtures\Application;

/**
 * Class DotenvLoaderTest
 * @package ByTIC\Dotenv\Tests\Support
 */
class DotenvLoaderTest extends AbstractTest
{
    public function test_safeLoad()
    {
        $application = new Application();
        DotenvLoader::safeLoad($application);

        static::assertSame('foe', getenv('TEST_STRING'));
    }
}