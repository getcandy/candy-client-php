<?php

namespace GetCandy\Client\Facades;

use Illuminate\Support\Facades\Facade;
use GetCandy\Client\Candy;

class CandyClient extends Facade
{
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
