<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Config;
use Session;

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
