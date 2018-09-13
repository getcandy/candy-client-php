<?php

namespace GetCandy\Client\Jobs\Products;

use GetCandy\Client\AbstractJob;

class Recommended extends AbstractJob
{
    protected $endpoint = 'products/recommended';
    protected $method = 'GET';
    protected $handle = 'products-recommended';
}
