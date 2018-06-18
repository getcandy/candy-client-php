<?php

namespace GetCandy\Client\Facades;

use Illuminate\Support\Facades\Facade;
use GetCandy\Client\Candy;
use GetCandy\Client\Testing\CandyClientFake;

class CandyClient extends Facade
{
    public static function fake()
    {
        static::swap($fake = new CandyClientFake());
        return $fake;
    }
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CandyClient::class;
    }
}
