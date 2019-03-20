<?php

namespace GetCandy\Client\Jobs\Payment;

use GetCandy\Client\AbstractJob;

class Get extends AbstractJob
{
    protected $endpoint = 'payments/provider';
    protected $handle = 'payment-get';
}
