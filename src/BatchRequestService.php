<?php

namespace GetCandy\Client;

use Cache;
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
use GetCandy\Client\Exceptions\ClientCredentialsException;

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

        $client = new Client(['base_uri' => CandyClient::getUrl()]);
        $promises = [];
        $headers = [
            'Authorization' => 'Bearer ' . $this->getToken($force),
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
            $promises[(string) $request] = $client->requestAsync($request->getMethod(), $request->getEndPoint(), $options);
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

    public function getToken($force = false)
    {
        return $this->getClientToken();
    }

    private function getRefreshToken($refreshToken)
    {
        $dateTime = Carbon::now();

        $params = [
            'client_id'     => CandyClient::getClientId(),
            'client_secret' => CandyClient::getClientSecret(),
            'scope'         => '',
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token'
        ];
        $response = $this->requestToken($params);

        // So if we don't get an access token back then try get a client token
        if (isset($response->access_token)) {
            Session::forget('logged_in');
            Session::forget('user');
            Session::forget('refresh_token');

            return $this->getClientToken();
        }

        return $response;
    }

    private function getClientToken()
    {
        if (!Cache::has('client_token')) {
            $params = [
                'client_id' => CandyClient::getClientId(),
                'client_secret' => CandyClient::getClientSecret(),
                'scope' => '',
                'grant_type' => 'client_credentials'
            ];
            $response = $this->requestToken($params);
            Cache::put('client_token', $response->access_token, ($response->expires_in / 60));
        }
        return Cache::get('client_token');
    }

    private function requestToken($params)
    {
        $client = new Client([
            'base_uri' => CandyClient::getUri()
        ]);

        try {
            $response = $client->post('oauth/token', [
                'form_params' => $params,
                'verify' => Config::get('services.ecommerce_api.verify'),
            ]);
        } catch (ClientException $e) {
            $body = json_decode($e->getResponse()->getBody()->getContents());
            throw new ClientCredentialsException($body->message);
        }


        return json_decode((string) $response->getBody());
    }
}
