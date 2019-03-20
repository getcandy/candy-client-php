<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;

class Post extends AbstractJob
{
    protected $endpoint = 'orders';
    protected $handle = 'orders-post';
}
