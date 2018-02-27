<?php

namespace GetCandy\Client\Jobs\Products;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Get extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/products';

        if (is_string($this->params)) {
            $action .= '/' . $this->params;
        }

        if (Session::has('excl_tax')) {
            if (parse_url($action, PHP_URL_QUERY)) {
                $action .= '&excl_tax='. boolVal(Session::get('excl_tax'));
            }else{
                $action .= '?excl_tax='. boolVal(Session::get('excl_tax'));
            }
        }

        $this->addRequest('products-get', new Request($action, 'GET', []));

    }
}
