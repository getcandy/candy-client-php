<?php

namespace GetCandy\Client\Jobs\Collections;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected $endpoint = 'collections';
    protected $handle = 'collections-get';
}
