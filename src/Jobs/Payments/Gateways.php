<?php

namespace GetCandy\Client\Jobs\Payments;

use GetCandy\Client\AbstractJob;

class Gateways extends AbstractJob
{
    protected $endpoint = 'payments/types';
    protected $handle = 'get-payment-gateways';
    protected $method = 'GET';
}
