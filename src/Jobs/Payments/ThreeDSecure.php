<?php

namespace GetCandy\Client\Jobs\Payments;

use GetCandy\Client\AbstractJob;

class ThreeDSecure extends AbstractJob
{
    protected $endpoint = 'payments/3d-secure';
    protected $handle = 'post-3d-secure';
    protected $method = 'POST';
}
