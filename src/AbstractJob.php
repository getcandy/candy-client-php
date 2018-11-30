<?php

namespace GetCandy\Client;

use Session;

abstract class AbstractJob implements JobInterface
{
    protected $method = 'GET';
    protected $requests = [];
    protected $params;

    public function __construct($params)
    {
        $this->setParams($params);
        $this->setup();
    }

    protected function setParams($params)
    {
        $this->params = $params;
    }

    protected function addRequest($key, Request $request)
    {
        $this->requests[$key] = $request;
    }

    protected function setup()
    {
        if (empty($this->method)) {
            $method = strtoupper(substr(strrchr(get_class($this), "\\"), 1));
        } else {
            $method = $this->method;
        }

        if (!empty($this->params['id'])) {
            // Check for {id}
            if (strpos($this->endpoint, '{id}') !== false) {
                $this->endpoint = str_replace('{id}', $this->params['id'], $this->endpoint);
            } else {
                $this->endpoint .= '/' . $this->params['id'];
            }
            unset($this->params['id']);
        }

        $request = new Request($this->endpoint, $method, $this->params);


        if (!empty($this->decorator)) {
            $request->setDecorator($this->decorator);
        }

        $this->addRequest($this->handle, $request);
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

    public function addResult($requestHash, $response, $failed = false)
    {
        foreach ($this->requests as $index => $request) {
            $thisRequestHash = (string) $request;
            if ($thisRequestHash == $requestHash) {
                $this->requests[$index]->setResponse($response, $failed);
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
