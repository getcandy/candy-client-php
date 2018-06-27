<?php

namespace GetCandy\Client\Jobs\Countries;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected $endpoint = 'countries';
    protected $handle = 'countries-get';
}
