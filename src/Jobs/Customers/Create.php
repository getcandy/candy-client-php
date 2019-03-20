<?php

namespace GetCandy\Client\Jobs\Customers;

use GetCandy\Client\AbstractJob;

class Create extends AbstractJob
{
    protected $endpoint = 'customers';
    protected $handle = 'customers-create';
    protected $method = 'post';
}
