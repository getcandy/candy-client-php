<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class PutShippingOption extends AbstractJob
{
    protected $method = 'PUT';
    protected $endpoint = 'orders/{id}/shipping/cost';
    protected $handle = 'orders-put-shipping-option';
    protected $idField = 'order_id';
}
