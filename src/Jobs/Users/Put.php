<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Put extends AbstractJob
{
    protected $endpoint = 'users';
    protected $handle = 'update-user';
}
