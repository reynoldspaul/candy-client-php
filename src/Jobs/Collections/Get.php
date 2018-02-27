<?php

namespace GetCandy\Client\Jobs\Collections;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/collections';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('collections-get', new Request($action, 'GET', []));

    }
}
