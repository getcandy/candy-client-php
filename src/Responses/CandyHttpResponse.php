<?php

namespace GetCandy\Client\Responses;

class CandyHttpResponse
{
    public function __toString()
    {
        return json_encode($this->data);
    }

    /**
     * The HTTP response code.
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * The response data.
     *
     * @var array
     */
    protected $data;

    /**
     * Any reason the for HTTP response.
     *
     * @var string
     */
    protected $reason;

    /**
     * The candy job.
     *
     * @var string
     */
    public $job;

    /**
     * Whether the request was fulfilled.
     *
     * @var bool
     */
    protected $fulfilled = true;

    public function __construct($job, $status = 200)
    {
        $this->statusCode = $status;
        $this->job = $job;
    }

    /**
     * Get the HTTP reason.
     *
     * @return void
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set whether the request was fulfilled.
     *
     * @param int $bool
     * @return self
     */
    public function setFulfilled($bool)
    {
        $this->fulfilled = $bool;

        return $this;
    }

    /**
     * Set the reason.
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
     * Set the status code.
     *
     * @param int $code
     * @return self
     */
    public function setStatusCode($code)
    {
        $this->statusCode = $code;

        return $this;
    }

    /**
     * Set the value for data.
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
     * The response data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return fulfilled value.
     *
     * @return bool
     */
    public function fulfilled()
    {
        return $this->fulfilled;
    }
}
