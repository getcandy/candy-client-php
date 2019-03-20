<?php

namespace GetCandy\Client\Jobs\Users;

use Config;
use GetCandy\Client\Request;
use GetCandy\Client\AbstractJob;

class Token extends AbstractJob
{
    protected function setup()
    {
        $action = 'oauth/token';

        $this->params['username'] = $this->params['email'];
        $this->params['grant_type'] = 'password';
        $this->params['client_id'] = Config::get('services.ecommerce_api.client_id');
        $this->params['client_secret'] = Config::get('services.ecommerce_api.client_secret');

        $this->addRequest('users-token', new Request($action, 'POST', $this->params));
    }
}
