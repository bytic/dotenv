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
}
