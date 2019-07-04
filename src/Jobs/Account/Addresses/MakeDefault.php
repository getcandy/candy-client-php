<?php

namespace GetCandy\Client\Jobs\Account\Addresses;

use GetCandy\Client\AbstractJob;

class MakeDefault extends AbstractJob
{
    protected $endpoint = 'addresses/{id}/default';
    protected $handle = 'make-default-address';
    protected $method = 'POST';
}
