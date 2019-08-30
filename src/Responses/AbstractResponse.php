<?php

namespace GetCandy\Client\Responses;

abstract class AbstractResponse
{
    protected $body = null;
    protected $status = 200;
    protected $failed = false;
    protected $meta = [];
    protected $links = [];

    public function getBody()
    {
        return $this->body;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function failed()
    {
        return $this->failed;
    }

    /**
     * @deprecated 0.2.0
     */
    public function hasFailed()
    {
        return $this->failed();
    }

    public function getMeta()
    {
        return $this->meta;
    }
}
