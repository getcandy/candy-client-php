<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;

class Current extends AbstractJob
{
    protected $endpoint = 'users/current';
    protected $method = 'GET';
    protected $handle = 'current-user';
}
