<?php

namespace GetCandy\Client\Responses;

class ErrorResponse extends AbstractResponse
{
    public function __construct($response)
    {
        $this->status = $response['error']['http_code'];
        $this->body = $response['error']['message'];
        $this->failed = true;
    }
}
