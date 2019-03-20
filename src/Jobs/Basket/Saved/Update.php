<?php

namespace GetCandy\Client\Jobs\Basket\Saved;

use GetCandy\Client\AbstractJob;

class Update extends AbstractJob
{
    protected $endpoint = 'baskets/saved/{id}';
    protected $handle = 'saved-basket-update';
    protected $method = 'PUT';
}
