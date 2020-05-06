<?php

namespace ByTIC\Dotenv\Tests\Fixtures;

use ByTIC\Dotenv\HasEnv\HasEnviroment;

/**
 * Class Application
 * @package ByTIC\Dotenv\Tests\Fixtures
 */
class Application
{
    use HasEnviroment;

    /**
     * @inheritDoc
     */
    protected function environmentPathGeneric()
    {
        return __DIR__;
    }
}
