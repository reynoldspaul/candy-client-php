<?php

namespace GetCandy\Client\Jobs\Routes;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected function setup()
    {

        $action = 'api/v1/routes';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('routes-get', new Request($action, 'GET', []));

    }

}
