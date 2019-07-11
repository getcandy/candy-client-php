<?php

namespace GetCandy\Client\Jobs\Basket\Lines;

use GetCandy\Client\AbstractJob;

class Update extends AbstractJob
{
    protected $endpoint = 'baskets/lines/{id}';
    protected $handle = 'update-basket-lines';
    protected $method = 'PUT';
    protected $idField = 'basket_line_id';
}
