<?php

namespace GetCandy\Client;

use Session;

abstract class AbstractJob implements JobInterface
{
    protected $requests = [];

    public function __construct($params = [])
    {
        $this->setParams($params);
        $this->setup();
    }

    protected function setParams($params)
    {
        if (is_string($params)) {
            $this->params['id'] = $params;
        }
        $this->params = $params;
    }

    protected function addRequest($key, Request $request)
    {
        $this->requests[$key] = $request;
    }

    protected function setup()
    {
        // Add your requests here
    }

    public function response($response)
    {
        return $response;
    }

    public function getRequests()
    {
        return $this->requests;
    }

    public function getRequest($key = null)
    {
        if ($key === null && count($this->requests) === 1) {
            return current($this->requests);
        }

        if (!isset($this->requests[$key])) {
            return false;
        }

        return $this->requests[$key];
    }

    public function addResult($requestHash, $response)
    {
        foreach ($this->requests as $index => $request) {
            $thisRequestHash = (string) $request;

            if ($thisRequestHash == $requestHash) {
                $this->requests[$index]->setResponse($response);
            }
        }
    }

    public function canRun()
    {
        foreach ($this->requests as $request) {
            if ($request->getResponse() === null) {
                return false;
            }
        }

        return true;
    }

    public function run()
    {
        // Add your code here
    }

    public function getReference()
    {
        return get_class($this);
    }
}
