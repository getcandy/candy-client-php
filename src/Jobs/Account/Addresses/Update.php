<?php

namespace GetCandy\Client\Jobs\Account\Addresses;

use GetCandy\Client\AbstractJob;

class Update extends AbstractJob
{
    protected $endpoint = 'addresses/{id}';
    protected $handle = 'update-address';
    protected $method = 'PUT';
}
