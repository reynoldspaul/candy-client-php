<?php

namespace GetCandy\Client\Facades;

use Illuminate\Support\Facades\Facade;

class GetCandy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'getcandy'; }
}
