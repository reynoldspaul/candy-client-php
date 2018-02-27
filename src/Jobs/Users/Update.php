<?php

namespace GetCandy\Client\Jobs\Users;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Update extends AbstractJob
{

    protected function setup()
    {
        $action = 'api/v1/users';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('users-update', new Request($action, 'POST', []));
    }

}
