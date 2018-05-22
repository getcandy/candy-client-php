<?php

namespace GetCandy\Client\Jobs\Products;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;
use GetCandy\Client\Responses\Product;

class Search extends AbstractJob
{
    protected $method = 'GET';
    protected $endpoint = 'search';
    protected $handle = 'search-products';
    protected $decorator = Product::class;
}
