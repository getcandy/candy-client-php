<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class Current extends AbstractJob
{
    protected $endpoint = 'baskets/current';
    protected $handle = 'basket-current';
    protected $idField = 'basket_id';
}
