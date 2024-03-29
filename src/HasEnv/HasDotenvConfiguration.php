<?php

declare(strict_types=1);

namespace ByTIC\Dotenv\HasEnv;

/**
 * Trait HasDotenvConfiguration.
 */
trait HasDotenvConfiguration
{
    /**
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     * The custom environment path defined by the developer.
     *
     * @var string
     */
    protected $environmentPath;

    /**
     * Get the path to the environment file directory.
     *
     * @return string
     */
    public function environmentPath()
    {
        return $this->environmentPath ?: $this->environmentPathGeneric();
    }

    /**
     * Set the environment file to be loaded during bootstrapping.
     *
     * @param string $file
     *
     * @return $this
     */
    public function loadEnvironmentFrom($file)
    {
        $this->environmentFile = $file;

        return $this;
    }

    /**
     * Load a custom environment file.
     *
     * @param string $file
     *
     * @return bool
     */
    public function checkLoadEnvironmentFilePath($file)
    {
        if (file_exists($this->environmentPath() . '/' . $file)) {
            $this->loadEnvironmentFrom($file);

            return true;
        }

        return false;
    }

    /**
     * Set the directory for the environment file.
     *
     * @param string $path
     *
     * @return $this
     */
    public function useEnvironmentPath($path)
    {
        $this->environmentPath = $path;

        return $this;
    }

    /**
     * Get the environment file the application is using.
     */
    public function environmentFile(): string
    {
        return $this->environmentFile ?: '.env';
    }

    /**
     * Get the fully qualified path to the environment file.
     *
     * @return string
     */
    public function environmentFilePath()
    {
        return $this->environmentPath() . '/' . $this->environmentFile();
    }

    abstract protected function environmentPathGeneric();
}
