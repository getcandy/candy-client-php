<?php

namespace GetCandy\Client\Jobs\Payments;

use GetCandy\Client\AbstractJob;

class Types extends AbstractJob
{
    protected $endpoint = 'payments/types';
    protected $handle = 'get-payment-gateways';
    protected $method = 'GET';
}
