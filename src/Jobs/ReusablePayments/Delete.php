<?php

namespace GetCandy\Client\Jobs\ReusablePayments;

use GetCandy\Client\AbstractJob;

class Delete extends AbstractJob
{
    protected $method = "DELETE";
    protected $endpoint = 'reusable-payments/{id}';
    protected $handle = 'reusable-payment-delete';
}
