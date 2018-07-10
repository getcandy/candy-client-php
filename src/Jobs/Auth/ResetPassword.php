<?php

namespace GetCandy\Client\Jobs\Auth;

use GetCandy\Client\AbstractJob;

class ResetPassword extends AbstractJob
{
    protected $endpoint = 'password/reset';
    protected $handle = 'auth-password-reset';
    protected $method = 'POST';
}
