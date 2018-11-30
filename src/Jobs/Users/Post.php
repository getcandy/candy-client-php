<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Post extends AbstractJob
{
    protected $endpoint = 'users';
    protected $handle = 'users-post';
}
