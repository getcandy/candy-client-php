<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected $endpoint = 'orders/{id}';
    protected $handle = 'orders-get';
}
