<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class ExtendExpiry extends AbstractJob
{
    protected $endpoint = 'orders/{id}/extend-expiry';
    protected $method = 'PUT';
    protected $handle = 'orders-extend-expiry';
}
