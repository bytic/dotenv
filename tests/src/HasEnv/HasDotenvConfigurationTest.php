<?php

declare(strict_types=1);

namespace ByTIC\Dotenv\Tests\HasEnv;

use ByTIC\Dotenv\Tests\AbstractTest;
use ByTIC\Dotenv\Tests\Fixtures\Application;

/**
 * Class HasDotenvConfigurationTest.
 */
class HasDotenvConfigurationTest extends AbstractTest
{
    public function testHasDotenvConfiguration()
    {
        $application = new Application();
        static::assertSame(TEST_FIXTURE_PATH . '/.env', $application->environmentFilePath());
    }

    public function testCustomFileAndPathRemainChainable()
    {
        $application = new Application();

        static::assertSame($application, $application->useEnvironmentPath(TEST_FIXTURE_PATH));
        static::assertSame($application, $application->loadEnvironmentFrom('.env.compat-app'));
        static::assertSame(TEST_FIXTURE_PATH . '/.env.compat-app', $application->environmentFilePath());
        static::assertFalse($application->checkLoadEnvironmentFilePath('.env.missing'));
        static::assertSame('.env.compat-app', $application->environmentFile());
    }
}
