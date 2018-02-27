<?php

namespace GetCandy\Client\Jobs\Basket;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Update extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/baskets';

        if (is_string($this->params)) {
            $action .=  $this->params;
        }

        $this->addRequest('basket-update', new Request($action, 'POST', []));
    }

    public function run()
    {
        $basket = $this->requests['basket-update']->getResponse();
        Session::put('basket_id', $basket['data']['id']);
    }
}
