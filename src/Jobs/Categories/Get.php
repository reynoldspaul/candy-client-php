<?php

namespace GetCandy\Client\Jobs\Categories;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;

class Get extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/categories';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('categories-get', new Request($action, 'GET', []));

    }
}
