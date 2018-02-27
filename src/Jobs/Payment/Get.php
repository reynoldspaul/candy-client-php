<?php

namespace GetCandy\Client\Jobs\Payment;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/payments/provider';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('payment-get', new Request($action, 'GET', []));

    }
}
