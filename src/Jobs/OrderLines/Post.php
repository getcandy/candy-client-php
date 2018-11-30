<?php

namespace GetCandy\Client\Jobs\OrderLines;

use GetCandy\Client\AbstractJob;

class Post extends AbstractJob
{
    protected $method = 'POST';
    protected $endpoint = 'orders/{id}/lines';
    protected $handle = 'orders-add-line';
    protected $idField = 'order_id';
}
