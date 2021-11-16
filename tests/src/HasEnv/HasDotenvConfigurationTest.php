<?php

namespace ByTIC\Dotenv\Tests\HasEnv;

use ByTIC\Dotenv\Tests\AbstractTest;
use ByTIC\Dotenv\Tests\Fixtures\Application;

/**
 * Class HasDotenvConfigurationTest
 * @package ByTIC\Dotenv\Tests\HasEnv
 */
class HasDotenvConfigurationTest extends AbstractTest
{
    public function test_has_dotenv_configuration()
    {
        $application = new Application();
        static::assertSame(TEST_FIXTURE_PATH . '/.env', $application->environmentFilePath());
    }
}