<?php

declare(strict_types=1);

namespace ByTIC\Dotenv\Tests\Support;

use ByTIC\Dotenv\Support\DotenvLoader;
use ByTIC\Dotenv\Tests\AbstractTest;
use ByTIC\Dotenv\Tests\Fixtures\Application;

/**
 * Class DotenvLoaderTest.
 */
class DotenvLoaderTest extends AbstractTest
{
    public function testSafeLoad()
    {
        $application = new Application();
        DotenvLoader::safeLoad($application);

        static::assertSame('foe', env('TEST_STRING'));
        static::assertEmpty(env('TEST_EMPTY'));

        static::assertSame('foe-var', env('TEST_VAR'));
        static::assertSame('rootpass', env('TEST_VAR_DEFAULT'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAppEnvironmentSelectsAnExistingFile()
    {
        $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'compat-app';
        $application = new Application();

        DotenvLoader::safeLoad($application);

        static::assertSame('.env.compat-app', $application->environmentFile());
        static::assertSame('app', $_ENV['BYTIC_COMPAT_VALUE']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testConsoleEnvironmentTakesPrecedenceWhenFileExists()
    {
        $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'compat-app';
        $_SERVER['argv'] = ['console', '--env=compat-cli'];
        $application = new class() extends Application {
            public function runningInConsole()
            {
                return true;
            }
        };

        DotenvLoader::safeLoad($application);

        static::assertSame('.env.compat-cli', $application->environmentFile());
        static::assertSame('cli', $_ENV['BYTIC_COMPAT_VALUE']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testMissingConsoleEnvironmentFallsBackToAppEnvironment()
    {
        $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'compat-app';
        $_SERVER['argv'] = ['console', '--env=missing'];
        $application = new class() extends Application {
            public function runningInConsole()
            {
                return true;
            }
        };

        DotenvLoader::safeLoad($application);

        static::assertSame('.env.compat-app', $application->environmentFile());
        static::assertSame('app', $_ENV['BYTIC_COMPAT_VALUE']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testBootLoadsCompiledEnvironmentAndNormalizesDebug()
    {
        unset($_SERVER['APP_ENV'], $_ENV['APP_ENV'], $_SERVER['APP_DEBUG'], $_ENV['APP_DEBUG']);
        putenv('APP_ENV');
        $application = new Application();
        $application->loadEnvironmentFrom('.env.compat-compiled');

        DotenvLoader::safeLoad($application);

        static::assertSame('compiled', $_ENV['BYTIC_COMPAT_VALUE']);
        static::assertSame('test', $_SERVER['APP_ENV']);
        static::assertSame('0', $_SERVER['APP_DEBUG']);
        static::assertSame('0', $_ENV['APP_DEBUG']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testBootIgnoresCompiledEnvironmentWhenItDoesNotMatch()
    {
        $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'prod';
        unset($_SERVER['APP_DEBUG'], $_ENV['APP_DEBUG']);
        $application = new Application();
        $application->loadEnvironmentFrom('.env.compat-compiled');

        DotenvLoader::safeLoad($application);

        static::assertSame('uncompiled', $_ENV['BYTIC_COMPAT_VALUE']);
        static::assertSame('prod', $_SERVER['APP_ENV']);
        static::assertSame('0', $_SERVER['APP_DEBUG']);
    }

    public function testInvalidFileIsReportedAndTerminates()
    {
        $code = 'require ' . var_export(TEST_BASE_PATH . '/bootstrap.php', true) . ';'
            . '$app = new \ByTIC\Dotenv\Tests\Fixtures\Application();'
            . '$app->loadEnvironmentFrom(".env.compat-invalid");'
            . '\ByTIC\Dotenv\Support\DotenvLoader::safeLoad($app);';
        $command = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($code) . ' 2>&1';

        exec($command, $output, $status);

        static::assertSame(1, $status);
        static::assertStringContainsString('The environment file is invalid!', implode("\n", $output));
    }
}
