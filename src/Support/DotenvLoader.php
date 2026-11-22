<?php

declare(strict_types=1);

namespace ByTIC\Dotenv\Support;

use ByTIC\Dotenv\HasEnv\HasEnviroment;
use Nip\Utility\Env;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\ExceptionInterface;

/**
 * Class DotenvLoader.
 */
class DotenvLoader
{
    /**
     * @param HasEnviroment $app
     */
    public static function safeLoad($app)
    {
        static::checkForSpecificEnvironmentFile($app);

        try {
            static::createDotenv($app);
        } catch (ExceptionInterface $e) {
            static::writeErrorAndDie($e);
        }
    }

    /**
     * Detect if a custom environment file matching the APP_ENV exists.
     *
     * @param HasEnviroment $app
     *
     * @return void
     */
    protected static function checkForSpecificEnvironmentFile($app)
    {
        if (method_exists($app, 'runningInConsole') && $app->runningInConsole()) {
            if (($input = new ArgvInput())->hasParameterOption('--env')) {
                if ($app->checkLoadEnvironmentFilePath(
                    $app->environmentFile() . '.' . $input->getParameterOption('--env')
                )) {
                    return;
                }
            }
        }

        $environment = Env::get('APP_ENV');

        if (!$environment) {
            return;
        }

        $app->checkLoadEnvironmentFilePath($app->environmentFile() . '.' . $environment);
    }

    /**
     * Create a Dotenv instance.
     *
     * @param HasEnviroment $app
     */
    protected static function createDotenv($app)
    {
        $dotenv = new Dotenv();
        $path = $app->environmentFilePath();

        if (method_exists($dotenv, 'bootEnv')) {
            $dotenv->bootEnv($path);

            return;
        }

        // Symfony 5.0 predates bootEnv(); preserve its default boot behavior.
        $compiledPath = $path . '.local.php';
        $compiled = is_file($compiledPath) ? include $compiledPath : null;
        $currentEnvironment = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null;

        if (is_array($compiled) && (!isset($compiled['APP_ENV']) || $currentEnvironment === null || $currentEnvironment === $compiled['APP_ENV'])) {
            $dotenv->populate($compiled);
        } else {
            $dotenv->loadEnv($path);
        }

        $_SERVER += $_ENV;
        $debug = $_SERVER['APP_DEBUG'] ?? ($_SERVER['APP_ENV'] !== 'prod');
        $_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int) $debug || (!is_bool($debug) && filter_var($debug, FILTER_VALIDATE_BOOLEAN)) ? '1' : '0';
    }

    /**
     * Write the error information to the screen and exit.
     *
     * @return void
     */
    protected static function writeErrorAndDie(ExceptionInterface $e)
    {
        $output = (new ConsoleOutput())->getErrorOutput();

        $output->writeln('The environment file is invalid!');
        $output->writeln($e->getMessage());

        exit(1);
    }
}
