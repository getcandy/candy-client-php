<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class PutShippingAddress extends AbstractJob
{
    protected $method = 'PUT';
    protected $endpoint = 'orders/{id}/shipping/address';
    protected $handle = 'orders-put-shipping-address';
    protected $idField = 'order_id';
}
