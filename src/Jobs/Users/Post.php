<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;

class Post extends AbstractJob
{
    protected $endpoint = 'users';
    protected $handle = 'users-post';
}
