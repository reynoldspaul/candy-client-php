<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Current extends AbstractJob
{

    protected function setup()
    {
        $action = 'api/v1/users/current';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('users-curren', new Request($action, 'GET', $this->params));
    }

}
