<?php

namespace ByTIC\Dotenv\Support;

use ByTIC\Dotenv\HasEnv\HasEnviroment;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

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
            static::createDotenv($app)->safeLoad();
        } catch (InvalidFileException $e) {
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
     * @return Dotenv
     */
    protected static function createDotenv($app)
    {
        return Dotenv::create(
            Env::getRepository(),
            $app->environmentPath(),
            $app->environmentFile()
        );
    }
    /**
     * Write the error information to the screen and exit.
     *
     * @param  \Dotenv\Exception\InvalidFileException  $e
     * @return void
     */
    protected static function writeErrorAndDie(InvalidFileException $e)
    {
        $output = (new ConsoleOutput())->getErrorOutput();

        $output->writeln('The environment file is invalid!');
        $output->writeln($e->getMessage());

        die(1);
    }
}
