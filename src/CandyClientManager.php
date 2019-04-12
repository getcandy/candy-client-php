<?php

namespace GetCandy\Client;

use Illuminate\Support\Manager;
use GetCandy\Client\Drivers\Guzzle;

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
     * Create the Guzzle driver instance.
     *
     * @return Guzzle
     */
    public function createGuzzleDriver()
    {
        return $this->buildProvider(
            Guzzle::class
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
        return new $provider($this->app);
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
