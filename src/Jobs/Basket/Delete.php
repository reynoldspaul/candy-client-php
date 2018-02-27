<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Delete extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/baskets';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('basket-delete', new Request($action, 'DELETE', []));

    }
}
