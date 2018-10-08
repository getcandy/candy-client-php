<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Put extends AbstractJob
{
    protected $endpoint = 'users/{id}';
    protected $method = 'PUT';
    protected $handle = 'update-user';
}
