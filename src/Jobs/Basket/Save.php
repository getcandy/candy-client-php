<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class Save extends AbstractJob
{
    protected $endpoint = 'baskets/{id}/save';
    protected $handle = 'basket-save';
    protected $method = 'POST';
    protected $idField = 'basket_id';
}
