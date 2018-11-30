<?php

namespace GetCandy\Client\Jobs\Account\Addresses;

use GetCandy\Client\AbstractJob;

class Create extends AbstractJob
{
    protected $endpoint = 'addresses';
    protected $handle = 'create-address';
    protected $method = 'POST';
}
