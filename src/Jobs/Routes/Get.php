<?php

namespace GetCandy\Client\Jobs\Routes;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected $endpoint = 'routes';
    protected $handle = 'routes-get';
}
