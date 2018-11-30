<?php

namespace GetCandy\Client\Jobs\Shipping;

use GetCandy\Client\AbstractJob;

class Estimate extends AbstractJob
{
    protected $endpoint = 'shipping/prices/estimate';
    protected $handle = 'get-estimate-shipping';
    protected $method = 'GET';
}
