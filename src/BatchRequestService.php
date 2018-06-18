<?php

namespace GetCandy\Client;

use Config;
use CandyClient;
use Carbon\Carbon;
use GuzzleHttp\Pool;
use Mockery\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Exception\ClientException;

class BatchRequestService
{
    protected $jobs = [];

    public function add(JobInterface $job, $reference = null)
    {
        $this->jobs[$reference] = $job;
    }

    public function getJobs()
    {
        return $this->jobs;
    }

    public function execute($force = false)
    {
        // Collect all API requests
        $requests = [];

        // Get unique requests
        foreach ($this->jobs as $job) {
            foreach ($job->getRequests() as $request) {
                if (!in_array($request, $requests)) {
                    $requests[(string) $request] = $request;
                }
            }
        }

        $client = CandyClient::getClient();

        $promises = [];

        $headers = [
            'Authorization' => 'Bearer ' . CandyClient::getToken($force),
            'Accept' => 'application/json'
        ];

        foreach ($requests as $request) {

            $options = [
                'headers' => $headers,
                'verify' => Config::get('services.ecommerce_api.verify'),
            ];

            if ($request->getData()) {
                if (strtoupper($request->getMethod()) == 'GET') {
                    $options['query'] = $request->getData();
                } else {
                    $options['form_params'] = $request->getData();
                }
            }

            $promises[(string) $request] = $client->requestAsync(
                $request->getMethod(),
                CandyClient::getUrl(
                    $request->getEndPoint()
                ),
                $options
            );
        }

        // Wait on all of the requests to complete. Throws a ConnectException if any of the requests fail
        $results = Promise\settle($promises)->wait();

        foreach ($promises as $index => $promise) {
            $response = $results[$index];
            foreach ($this->jobs as $job) {
                $job->addResult($index, $response);
                if ($job->canRun()) {
                    $job->run();
                }
            }
        }
    }
}
