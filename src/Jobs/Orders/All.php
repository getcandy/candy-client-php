<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class All extends AbstractJob
{
    protected $method = 'GET';
    protected $endpoint = 'orders';
    protected $handle = 'orders-all';
}
