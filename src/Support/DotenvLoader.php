<?php

namespace ByTIC\Dotenv\Support;

use ByTIC\Dotenv\HasEnv\HasEnviroment;
use Nip\Utility\Env;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\ExceptionInterface;

/**
 * Class DotenvLoader
 * @package ByTIC\Dotenv\HasEnv\Support
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
        (new Dotenv())
            ->bootEnv($app->environmentFilePath());
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

        die(1);
    }
}
