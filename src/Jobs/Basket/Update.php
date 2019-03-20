<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class Update extends AbstractJob
{
    protected $endpoint = 'baskets';
    protected $handle = 'basket-post';
    protected $method = 'POST';
    protected $idField = 'basket_id';
}
