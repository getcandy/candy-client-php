<?php

namespace GetCandy\Client\Testing;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    protected $payload = [];
    protected $headers;
    protected $status;

    public function __construct($status = 200, $data = [], $headers = [])
    {
        $this->status = $status;
        $this->payload = $data;
        $this->headers = $headers;
    }

    public function getMock()
    {
        return new GuzzleResponse($this->status, $this->headers, json_encode($this->payload));
    }
}
