<?php

namespace ByTIC\Dotenv\Support;

use ByTIC\Dotenv\HasEnv\HasEnviroment;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;


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
        try {
            static::createDotenv($app)->safeLoad();
        } catch (InvalidFileException $e) {
            static::writeErrorAndDie($e);
        }
    }

    /**
     * Create a Dotenv instance.
     *
     * @param HasEnviroment $app
     * @return \Dotenv\Dotenv
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
//        $output = (new ConsoleOutput)->getErrorOutput();
//
//        $output->writeln('The environment file is invalid!');
//        $output->writeln($e->getMessage());

        echo $e->getMessage();

        die(1);
    }
}
