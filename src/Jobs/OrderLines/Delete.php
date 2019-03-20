<?php

namespace GetCandy\Client\Jobs\OrderLines;

use GetCandy\Client\AbstractJob;

class Delete extends AbstractJob
{
    protected $method = 'DELETE';
    protected $endpoint = 'orders/lines/{id}';
    protected $handle = 'orders-add-line';
    protected $idField = 'order_id';
}
