<?php

namespace GetCandy\Client\Jobs\Countries;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{

    protected function setup()
    {
        $action = 'api/v1/countries';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('countries-get', new Request($action, 'GET', []));
    }

    

}
