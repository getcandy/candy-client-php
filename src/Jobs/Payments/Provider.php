<?php

namespace GetCandy\Client\Jobs\Payments;

use GetCandy\Client\AbstractJob;

class Provider extends AbstractJob
{
    protected $endpoint = 'payments/provider';
    protected $handle = 'get-payment-provider';
    protected $method = 'GET';
}
