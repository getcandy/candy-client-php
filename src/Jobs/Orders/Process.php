<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class Process extends AbstractJob
{
    protected $method = 'POST';
    protected $endpoint = 'orders/process';
    protected $handle = 'orders-process';
    protected $idField = 'order_id';
}
