<?php

namespace GetCandy\Client\Jobs\Channels;

use GetCandy\Client\AbstractJob;

class Get extends AbstractJob
{
    protected $endpoint = 'channels';
    protected $handle = 'channels-get';
}
