<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Create extends AbstractJob
{
    protected $endpoint = 'orders';
    protected $handle = 'orders-create';
    protected $method = 'POST';
    protected $idField = 'basket_id';
}
