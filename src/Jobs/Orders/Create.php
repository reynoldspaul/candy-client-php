<?php

namespace GetCandy\Client\Jobs\Orders;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Create extends AbstractJob
{

    protected function setup()
    {
        $action = 'api/v1/orders';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        $this->addRequest('orders-create', new Request($action, 'POST', $this->params));
    }

    public function run()
    {
        $order = $this->requests['orders-create']->getResponse();
        Session::put('order_id', $order['data']['id']);
    }

}
