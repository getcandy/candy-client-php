<?php

namespace GetCandy\Client\Drivers;

use CandyClient;
use GetCandy\Client\JobInterface;
use Illuminate\Foundation\Application;

abstract class AbstractDriver
{
    protected $jobs = [];

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the default headers.
     *
     * @param bool $force
     * @return void
     */
    protected function getDefaultHeaders($force = false)
    {
        return [
            'Authorization' => 'Bearer '.CandyClient::getToken($force),
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-CANDYAPI' => 'TRUE',
            'X-CANDY-CURRENCY' => CandyClient::getCurrency(),
            'X-CANDY-CHANNEL' => CandyClient::getChannel(),
            'Accept-Language' => $this->app->getLocale(),
        ];
    }

    /**
     * Add a job to the queue.
     *
     * @param JobInterface $job
     * @param string|null $reference
     * @return void
     */
    public function add(JobInterface $job, $reference = null)
    {
        $this->jobs[$reference] = $job;
    }

    /**
     * Get the jobs.
     *
     * @return array|Collection
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Execute the queue of jobs.
     *
     * @param bool $force
     * @return void
     */
    abstract public function execute($force = false);
}
