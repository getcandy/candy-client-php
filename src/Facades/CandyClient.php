<?php

namespace GetCandy\Client\Facades;

use Illuminate\Support\Facades\Facade;
use GetCandy\Client\Testing\CandyClientFake;

class CandyClient extends Facade
{
    public static function fake($app = null)
    {
        static::swap($fake = new CandyClientFake($app ?: app()->getInstance()));

        return $fake;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return self::class;
    }
}
