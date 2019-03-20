<?php

namespace GetCandy\Client\Jobs\Auth;

use GetCandy\Client\Request;
use GetCandy\Client\AbstractJob;

class Post extends AbstractJob
{
    protected function setup()
    {
        $action = 'oauth/token';

        if (is_string($this->params)) {
            $action .= '/'.$this->params;
        }

        $this->addRequest('auth-post', new Request($action, 'POST', $this->params));
    }
}
