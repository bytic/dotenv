<?php

namespace ByTIC\Dotenv\HasEnv;

/**
 * Trait HasDotenvConfiguration
 * @package ByTIC\Dotenv
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
     * @param  string $file
     * @return $this
     */
    public function loadEnvironmentFrom($file)
    {
        $this->environmentFile = $file;
        return $this;
    }

    /**
     * Set the directory for the environment file.
     *
     * @param  string $path
     * @return $this
     */
    public function useEnvironmentPath($path)
    {
        $this->environmentPath = $path;
        return $this;
    }

    /**
     * Get the environment file the application is using.
     *
     * @return string
     */
    public function environmentFile()
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
