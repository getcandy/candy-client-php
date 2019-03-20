<?php

namespace GetCandy\Client\Jobs\Account;

use GetCandy\Client\Request;
use GetCandy\Client\AbstractJob;

class Password extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/account/password';

        if (is_string($this->params)) {
            $action .= '/'.$this->params;
        }

        $this->addRequest('account-password', new Request($action, 'POST', $this->params));
    }
}
