<?php

namespace GetCandy\Client;

use Closure;

class Candy
{
    protected $callChain = [];
    protected $returnJob = false;
    protected $job;

    protected $params;
    protected $url;
    protected $clientId;
    protected $clientSecret;
    protected $channel = 'webstore';
    protected $locale = 'en';
    /**
     * Sets up the client
     *
     * @param string $url
     * @param array $config
     * @return void
     */
    public function init($url, array $config = [])
    {
        $this->url = $url;
        $this->clientSecret = $config['client_secret'] ?? null;
        $this->clientId = $config['client_id'] ?? null;
        $this->locale = $config['locale'] ?? $this->locale;
        $this->channel = $config['channel'] ?? $this->channel;
    }

    public function getUrl($endpoint = null)
    {
        return $this->url . '/api/v1/' . $endpoint;
    }

    public function getUri()
    {
        return $this->url;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function __call($name, $arguments)
    {
        $this->callChain[] = ucfirst($name);

        if (!empty($arguments)) {
            $this->addParams($arguments);
        }

        if ($this->canRun()) {
            if ($this->returnJob) {
                return $this->getJob();
            } else {
                return $this->execute()->getResponse();
            }
        }

        return $this;
    }

    protected function addParams($params)
    {
        if (!is_array($params[0])) {
            $this->params['id'] = $params[0];
        } else {
            foreach ($params[0] as $key => $value) {
                $this->params[$key] = $value;
            }
        }
    }

    public function job()
    {
        $this->returnJob = true;

        return $this;
    }

    public function canRun()
    {
        // Do we have a job for this call chain?
        return class_exists($this->getClassFromChain());
    }

    public function getJob()
    {
        $jobClass = $this->getClassFromChain();
        $this->job = new $jobClass($this->params);

        $this->reset();

        return $this->job;
    }

    public function execute()
    {
        $job = $this->getJob();
        $batch = new BatchRequestService();
        $batch->add($job);
        $batch->execute();

        $this->reset();

        return $job->getRequest();
    }

    public function batch(Closure $addJobs)
    {
        $batch = new \GetCandy\Client\BatchRequestService();

        $addJobs($batch);

        $batch->execute();

        $responses = [];

        foreach ($batch->getJobs() as $key => $job) {
            $responses[$key] = $job->getRequest()->getResponse();
        }

        return $responses;
    }

    public function reset()
    {
        $this->callChain = [];
        $this->returnJob = false;
    }

    private function getClassFromChain()
    {
        return "\\GetCandy\\Client\\Jobs\\" . implode("\\", array_map("ucfirst", $this->callChain));
    }
}
