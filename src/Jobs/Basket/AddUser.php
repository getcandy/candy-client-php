<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;

class AddUser extends AbstractJob
{
    protected $endpoint = 'baskets/{id}/user';
    protected $handle = 'basket-add-user';
    protected $method = 'PUT';
    protected $idField = 'basket_id';
}
