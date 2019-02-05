<?php

namespace GetCandy\Client\Responses;

class CandyHttpResponse
{
    /**
     * The HTTP response code
     *
     * @var integer
     */
    protected $statusCode = 200;

    /**
     * The response data
     *
     * @var array
     */
    protected $data;

    /**
     * Any reason the for HTTP response
     *
     * @var string
     */
    protected $reason;

    /**
     * Whether the request was fulfilled
     *
     * @var boolean
     */
    protected $fulfilled = true;

    public function __construct($status = 200)
    {
        $this->statusCode = $status;
    }

    /**
     * Get the HTTP reason
     *
     * @return void
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set whether the request was fulfilled
     *
     * @param integer $bool
     * @return self
     */
    public function setFulfilled($bool)
    {
        $this->fulfilled = $bool;
        return $this;
    }

    /**
     * Set the reason
     *
     * @param string $reason
     * @return self
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the status code
     *
     * @param integer $code
     * @return self
     */
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Set the value for data
     *
     * @param array $data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * The response data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return fulfilled value
     *
     * @return boolean
     */
    public function fulfilled()
    {
        return $this->fulfilled;
    }
}
