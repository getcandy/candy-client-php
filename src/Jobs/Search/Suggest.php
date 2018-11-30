<?php

namespace GetCandy\Client\Jobs\Search;

use GetCandy\Client\AbstractJob;

class Suggest extends AbstractJob
{
    protected $endpoint = 'search/suggest';
    protected $handle = 'search-suggest';
    protected $method = 'GET';
}
