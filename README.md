# Dotenv

A backwards-compatible wrapper for [Symfony Dotenv](https://symfony.com/doc/current/configuration.html#configuring-environment-variables-in-env-files).

## Requirements

PHP 7.2.5 or later and Symfony Dotenv and Console 5, 6, 7 or 8. Each Symfony major has its own PHP requirements; in particular, Symfony 8 requires PHP 8.4 or later. Composer selects compatible versions for your PHP version.

## Usage

Implement `environmentPathGeneric()` on your application and use the existing `HasEnviroment` trait:

```php
use ByTIC\Dotenv\HasEnv\HasEnviroment;
use ByTIC\Dotenv\Support\DotenvLoader;

class Application
{
    use HasEnviroment;

    protected function environmentPathGeneric()
    {
        return __DIR__;
    }
}

$app = new Application();
DotenvLoader::safeLoad($app);
```

By default, the loader starts with `.env` in the application's environment path. Use `useEnvironmentPath($path)` and `loadEnvironmentFrom($file)` to configure it. If an application reports `runningInConsole()`, an existing file suffixed with the `--env` option takes precedence; otherwise, an existing file suffixed with `APP_ENV` is selected. If neither exists, the configured file is used. Symfony's `bootEnv()` then loads the selected file and its related environment files. Existing environment variables are not overridden. Invalid environment files are reported to stderr and terminate the process with exit code 1, as before.

## Development

Run `vendor/bin/phpunit` after installing dependencies. The PHP-CS-Fixer configuration uses the Symfony ruleset as a starting point for gradual style migration; run `vendor/bin/php-cs-fixer fix --dry-run --diff` to review changes. Run the fixer with a PHP version compatible with the minimum supported PHP version, so it does not introduce unsupported syntax.

#### Inspiration
* https://github.com/sroze/companienv