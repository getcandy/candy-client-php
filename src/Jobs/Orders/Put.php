<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class Put extends AbstractJob
{
    protected $endpoint = 'orders/{id}';
    protected $method = 'PUT';
    protected $handle = 'orders-put';
}
