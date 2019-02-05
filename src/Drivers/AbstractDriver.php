<?php

namespace GetCandy\Client\Drivers;

use CandyClient;
use GetCandy\Client\JobInterface;

abstract class AbstractDriver
{
    protected $jobs = [];

    /**
     * Get the default headers
     *
     * @param boolean $force
     * @return void
     */
    protected function getDefaultHeaders($force = false)
    {
        return [
            'Authorization' => 'Bearer ' . CandyClient::getToken($force),
            'accept' => 'application/json',
        ];
    }

    /**
     * Add a job to the queue
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
     * Get the jobs
     *
     * @return array|Collection
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Execute the queue of jobs
     *
     * @param boolean $force
     * @return void
     */
    abstract function execute($force = false);
}
