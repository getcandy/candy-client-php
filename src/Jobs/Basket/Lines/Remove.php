<?php

namespace GetCandy\Client\Jobs\Basket\Lines;

use GetCandy\Client\AbstractJob;

class Remove extends AbstractJob
{
    protected $endpoint = 'baskets/lines/{id}/remove';
    protected $handle = 'basket-line-remove-quantity';
    protected $method = 'POST';
    protected $idField = 'basket_line_id';
}
