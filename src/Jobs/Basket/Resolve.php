<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class Resolve extends AbstractJob
{
    protected $endpoint = 'baskets/resolve';
    protected $handle = 'basket-resolve';
    protected $method = 'POST';
    protected $idField = 'basket_id';
}
