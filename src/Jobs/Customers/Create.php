<?php

namespace GetCandy\Client\Jobs\Customers;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Create extends AbstractJob
{
    protected $endpoint = 'customers';
    protected $handle = 'customers-create';
    protected $method = 'post';
}
