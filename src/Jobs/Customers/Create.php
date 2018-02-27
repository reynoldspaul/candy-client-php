<?php

namespace GetCandy\Client\Jobs\Customers;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Create extends AbstractJob
{

    protected function setup()
    {
        $action = 'api/v1/customers';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('customers-create', new Request($action, 'POST', $this->params));
    }

}
