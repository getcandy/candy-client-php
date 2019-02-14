<?php

namespace GetCandy\Client\Jobs\Basket\Discounts;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Delete extends AbstractJob
{
    protected $endpoint = 'baskets/{id}/discounts';
    protected $handle = 'basket-delete-discount';
    protected $method = 'DELETE';
    protected $idField = 'basket_id';
}
