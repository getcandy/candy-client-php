<?php

namespace GetCandy\Client\Jobs\Basket\Lines;

use GetCandy\Client\AbstractJob;

class Add extends AbstractJob
{
    protected $endpoint = 'basket-lines/{id}/add';
    protected $handle = 'basket-line-add-quantity';
    protected $method = 'POST';
    protected $idField = 'basket_line_id';
}
