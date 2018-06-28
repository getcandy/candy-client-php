<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class Billing extends AbstractJob
{
    protected $method = 'PUT';
    protected $endpoint = 'orders/{id}/billing/address';
    protected $handle = 'orders-put-billing-address';
    protected $idField = 'order_id';
}
