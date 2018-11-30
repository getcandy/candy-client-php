<?php

namespace GetCandy\Client\Jobs\Account\Addresses;

use GetCandy\Client\AbstractJob;

class Delete extends AbstractJob
{
    protected $endpoint = 'addresses/{id}';
    protected $handle = 'delete-address';
    protected $method = 'DELETE';
}
