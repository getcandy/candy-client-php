<?php

namespace GetCandy\Client;

use GetCandy\Client\Responses\ErrorResponse;
use GetCandy\Client\Responses\SuccessResponse;

class Request
{
    protected $endPoint;
    protected $method = 'get';
    protected $data;
    protected $response;
    protected $decorator = null;

    public function __construct($endPoint, $method, $data = [])
    {
        $this->endPoint = $endPoint;
        $this->method = $method;
        $this->data = $data;
    }

    public function setDecorator($decorator)
    {
        $this->decorator = $decorator;
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

    public function setResponse($response, $failed = false)
    {
        if ($failed) {
            $this->response = new ErrorResponse($response);
        } else {
            $this->response = new SuccessResponse($response);
        }
    }

    public function __toString()
    {
         return md5($this->endPoint . $this->method . json_encode($this->data));
    }
}
