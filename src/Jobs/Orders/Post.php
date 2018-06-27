<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Post extends AbstractJob
{
    protected $endpoint = 'orders';
    protected $handle = 'orders-post';
}
