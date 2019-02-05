<?php

namespace GetCandy\Client\Drivers;

use CandyClient;
use Request as HttpRequest;
use GetCandy\Client\Request;
use GetCandy\Client\InternalRequest;
use Illuminate\Http\JsonResponse;
use GetCandy\Client\Responses\CandyHttpResponse;

class Internal extends AbstractDriver
{
    public function execute($force = false)
    {
        // Collect all API requests
        $requests = [];

        // Get unique requests
        foreach ($this->jobs as $job) {
            foreach ($job->getRequests() as $request) {
                if (!in_array($request, $requests)) {
                    $requests[(string) $request] = $this->buildRequest($request);
                }
            }
        }

        $client = CandyClient::getClient();

        $responses = collect();

        foreach ($requests as $index => $request) {
            $response = $this->parseResponse(
                $request->make()->run()
            );
            foreach ($this->jobs as $job) {
                $job->addResult($index, $response);
                if ($job->canRun()) {
                    $job->run();
                }
            }
        }
    }

    /**
     * Parses the response so API response can render it
     *
     * @param mixed $response
     * @return CandyResponse
     */
    protected function parseResponse($response)
    {
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
        } else {
            $data = json_decode($response->getContent(), true);
        }
        $httpResponse = new CandyHttpResponse($response->getStatusCode());
        $httpResponse->setData($data);

        if ($response->getStatusCode() >= 400) {
            $httpResponse->setFulfilled(false);
        }
        return $httpResponse;
    }

    /**
     * Builds the request
     *
     * @param Request $request
     * @return InternalRequest
     */
    protected function buildRequest(Request $request)
    {
        $http = HttpRequest::create(
            CandyClient::getUrl($request->getEndPoint()),
            $request->getMethod()
        );

        return (new InternalRequest($http))
            ->setUrl(CandyClient::getUrl($request->getEndPoint()))
            ->setMethod($request->getMethod())
            ->setParameters($request->getData())
            ->setHeaders($this->getDefaultHeaders());
    }
}
