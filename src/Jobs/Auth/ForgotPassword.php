<?php

namespace GetCandy\Client\Jobs\Auth;

use GetCandy\Client\AbstractJob;

class ForgotPassword extends AbstractJob
{
    protected $endpoint = 'password/reset/request';
    protected $handle = 'auth-request-password-reset';
    protected $method = 'POST';
}
