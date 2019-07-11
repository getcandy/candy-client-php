<?php

namespace GetCandy\Client\Jobs\Basket\Lines;

use GetCandy\Client\AbstractJob;

class Delete extends AbstractJob
{
    protected $endpoint = 'baskets/lines';
    protected $handle = 'delete-basket-line';
    protected $method = 'DELETE';
}
