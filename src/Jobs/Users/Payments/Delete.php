<?php

namespace GetCandy\Client\Jobs\Users\Payments;

use GetCandy\Client\AbstractJob;

class Delete extends AbstractJob
{
    protected $endpoint = 'users/payments/{id}';
    protected $method = 'DELETE';
    protected $handle = 'delete-reusable-payment';
}
