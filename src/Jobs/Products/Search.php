<?php

namespace GetCandy\Client\Jobs\Products;

use GetCandy\Client\AbstractJob;
use GetCandy\Client\Request;
use Session;

class Search extends AbstractJob
{
    protected function setup()
    {
        $action = 'api/v1/search';

        if (Session::has('excl_tax')) {
            $this->params['excl_tax'] = boolVal(Session::get('excl_tax'));
        }

        $this->addRequest('products-search', new Request($action, 'GET', $this->params));
    }
}
