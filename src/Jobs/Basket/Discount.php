<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class Discount extends AbstractJob
{
    protected $method = 'PUT';
    protected $endpoint = 'baskets/{id}/discounts';
    protected $handle = 'basket-current';
    protected $idField = 'basket_id';
}
