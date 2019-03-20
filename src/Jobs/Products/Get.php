<?php

namespace GetCandy\Client\Jobs\Products;

use GetCandy\Client\AbstractJob;

class Get extends AbstractJob
{
    protected $endpoint = 'products';
    protected $method = 'GET';
    protected $handle = 'products-get';
}
