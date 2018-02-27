<?php

namespace GetCandy\Client\Jobs\Channels;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/channels';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('channels-get', new Request($action, 'GET', []));
    }
}
