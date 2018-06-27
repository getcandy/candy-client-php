<?php

namespace GetCandy\Client\Jobs\Channels;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected $endpoint = 'channels';
    protected $handle = 'channels-get';
}
