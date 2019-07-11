<?php

namespace GetCandy\Client\Jobs\Basket\Lines;

use GetCandy\Client\AbstractJob;

class Create extends AbstractJob
{
    protected $endpoint = 'baskets/{id}/lines';
    protected $handle = 'basket-add-lines';
    protected $method = 'POST';
    protected $idField = 'basket_id';
}
