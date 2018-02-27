<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Post extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/orders';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('orders-put', new Request($action, 'POST', []));

    }
}
