<?php

namespace GetCandy\Client;

use Session;

class Request
{
    protected $endPoint;
    protected $method = 'get';
    protected $data;
    protected $response;

    public function __construct($endPoint, $method, $data)
    {

        if (parse_url($endPoint, PHP_URL_QUERY)) {
            $endPoint .= '&channel='. env('APP_CHANNEL', 'aqua-spa-supplies');
        }else{
            $endPoint .= '?channel='. env('APP_CHANNEL', 'aqua-spa-supplies');
        }

        $this->endPoint = $endPoint;
        $this->method = $method;
        $this->data = $data;
    }

    public function getEndPoint()
    {
        return $this->endPoint;
    }

    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function __toString()
    {
         return md5($this->endPoint . $this->method . json_encode($this->data));
    }
}
