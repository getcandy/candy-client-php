<?php

namespace GetCandy\Client\Jobs\Collections;

use GetCandy\Client\AbstractJob;

class Get extends AbstractJob
{
    protected $endpoint = 'collections';
    protected $handle = 'collections-get';
}
