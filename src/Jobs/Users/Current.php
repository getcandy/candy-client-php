<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Current extends AbstractJob
{
    protected $endpoint = 'users/current';
    protected $method = 'GET';
    protected $handle = 'products-get';
}
