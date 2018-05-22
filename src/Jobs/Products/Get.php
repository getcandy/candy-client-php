<?php

namespace GetCandy\Client\Jobs\Products;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Get extends AbstractJob
{
    protected $endpoint = 'products';
    protected $method = 'GET';
    protected $handle = 'products-get';
}
