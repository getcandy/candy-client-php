<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\Request;
use GetCandy\Client\AbstractJob;

class Put extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/baskets';

        if (is_string($this->params)) {
            $action .= '/'.$this->params;
        }

        $this->addRequest('basket-put', new Request($action, 'PUT', []));
    }
}
