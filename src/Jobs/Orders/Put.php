<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Put extends AbstractJob
{
    protected $endpoint = 'orders/{id}';
    protected $method = 'PUT';
    protected $handle = 'orders-put';
}
