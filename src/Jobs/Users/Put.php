<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;

class Put extends AbstractJob
{
    protected $endpoint = 'users/{id}';
    protected $method = 'PUT';
    protected $handle = 'update-user';
}
