<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class Get extends AbstractJob
{
    protected $endpoint = 'baskets';
    protected $handle = 'basket-get';
    protected $idField = 'basket_id';
}
