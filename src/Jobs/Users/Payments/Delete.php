<?php

namespace GetCandy\Client\Jobs\Users\Payments;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Delete extends AbstractJob
{
    protected $endpoint = 'users/payments/{id}';
    protected $method = 'DELETE';
    protected $handle = 'delete-reusable-payment';
}
