<?php

namespace GetCandy\Client\Jobs\Shipping;

use GetCandy\Client\AbstractJob;

class Methods extends AbstractJob
{
    protected $endpoint = 'shipping';
    protected $handle = 'get-shipping-methods';
    protected $method = 'GET';
}
