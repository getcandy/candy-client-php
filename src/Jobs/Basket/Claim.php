<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class Claim extends AbstractJob
{
    protected $endpoint = 'baskets/{id}/claim';
    protected $handle = 'basket-claim';
    protected $method = 'POST';
    protected $idField = 'basket_id';
}
