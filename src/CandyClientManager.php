<?php

namespace GetCandy\Client;

use Illuminate\Support\Manager;
use GetCandy\Client\Drivers\Guzzle;
use GetCandy\Client\Drivers\Internal;

class CandyClientManager extends Manager
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Create the Guzzle driver instance
     *
     * @return Guzzle
     */
    public function createGuzzleDriver()
    {
        return $this->buildProvider(
            Guzzle::class
        );
    }

    public function createInternalDriver()
    {
        return $this->buildProvider(
            Internal::class
        );
    }

    /**
     * Build a layout provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return
     */
    public function buildProvider($provider)
    {
        return new $provider;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return Guzzle::class;
    }
}
