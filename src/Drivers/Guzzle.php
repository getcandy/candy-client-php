<?php

namespace GetCandy\Client\Drivers;

use Config;
use CandyClient;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Response;
use GetCandy\Client\Responses\CandyHttpResponse;

class Guzzle extends AbstractDriver
{
    public function execute($force = false)
    {
        // Collect all API requests
        $requests = [];

        // Get unique requests
        foreach ($this->jobs as $job) {
            foreach ($job->getRequests() as $request) {
                if (! in_array($request, $requests)) {
                    $requests[(string) $request] = $request;
                }
            }
        }

        $client = CandyClient::getClient();

        $promises = [];

        $headers = [
            'Authorization' => 'Bearer '.CandyClient::getToken($force),
            'Accept' => 'application/json',
        ];

        foreach ($requests as $request) {
            $options = [
                'headers' => $headers,
                'verify' => Config::get('services.ecommerce_api.verify'),
            ];

            if ($request->getData()) {
                $method = strtoupper($request->getMethod());
                if ($method == 'GET' || $method == 'PUT') {
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
                $job->addResult($index, $this->parseResponse($job, $response));
                if ($job->canRun()) {
                    $job->run();
                }
            }
        }
    }

    /**
     * Parse the guzzle response.
     *
     * @param mixed $response
     * @return void
     */
    protected function parseResponse($job, $response)
    {
        $psr = $response['value'] ?? $response;

        $fulfilled = true;
        if ($response['state'] == 'rejected') {
            $psr = $response['reason']->getResponse();
            $fulfilled = false;
        }

        $data = json_decode($psr->getBody()->getContents(), true);

        $httpResponse = new CandyHttpResponse($job, $psr->getStatusCode());
        $httpResponse->setData($data);
        $httpResponse->setFulfilled($fulfilled);

        return $httpResponse;
    }
}
