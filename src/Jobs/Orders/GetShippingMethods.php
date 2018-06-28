<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class GetShippingMethods extends AbstractJob
{
    protected $method = 'GET';
    protected $endpoint = 'orders/{id}/shipping/methods';
    protected $handle = 'orders-get-shipping-methods';
    protected $idField = 'id';
}
