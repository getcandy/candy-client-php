<?php

namespace GetCandy\Client\Jobs\Search;

use GetCandy\Client\AbstractJob;

class Sku extends AbstractJob
{
    protected $endpoint = 'search/sku';
    protected $handle = 'search-sku';
    protected $method = 'GET';
}
