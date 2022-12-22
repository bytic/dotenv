<?php

declare(strict_types=1);

namespace ByTIC\Dotenv\Tests\Fixtures;

use ByTIC\Dotenv\HasEnv\HasEnviroment;

/**
 * Class Application.
 */
class Application
{
    use HasEnviroment;

    /**
     * {@inheritDoc}
     */
    protected function environmentPathGeneric()
    {
        return __DIR__;
    }
}
