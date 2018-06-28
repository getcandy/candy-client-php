<?php

namespace GetCandy\Client\Jobs\Shipping;

use GetCandy\Client\AbstractJob;

class Countries extends AbstractJob
{
    protected $endpoint = 'countries';
    protected $handle = 'get-countries';
    protected $method = 'GET';
}
