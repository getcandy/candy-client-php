<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class Contact extends AbstractJob
{
    protected $method = 'PUT';
    protected $endpoint = 'orders/{id}/contact';
    protected $handle = 'orders-put-contact';
    protected $idField = 'order_id';
}
