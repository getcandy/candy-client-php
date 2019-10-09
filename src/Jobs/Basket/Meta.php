<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class Meta extends AbstractJob
{
    protected $endpoint = 'baskets/{id}/meta';
    protected $handle = 'basket-meta';
    protected $method = 'POST';
}
