<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/baskets';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('basket-get', new Request($action, 'GET', []));

    }
}
