<?php

namespace GetCandy\Client\Drivers;

use CandyClient;
use Request as HttpRequest;
use GetCandy\Client\Request;
use Illuminate\Http\JsonResponse;
use GetCandy\Client\InternalRequest;
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
                if (! in_array($request, $requests)) {
                    $requests[(string) $request] = $this->buildRequest($request);
                }
            }
        }

        $responses = collect();

        foreach ($requests as $index => $request) {
            foreach ($this->jobs as $job) {
                $job->addResult($index, $this->parseResponse($job, $request->make()->run()));
                if ($job->canRun()) {
                    $job->run();
                }
            }
        }
    }

    /**
     * Parses the response so API response can render it.
     *
     * @param mixed $response
     * @return CandyResponse
     */
    protected function parseResponse($job, $response)
    {
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
        } else {
            $data = json_decode($response->getContent(), true);
        }
        $httpResponse = new CandyHttpResponse($job->getHandle(), $response->getStatusCode());
        $httpResponse->setData($data);

        if ($response->getStatusCode() >= 400) {
            $httpResponse->setFulfilled(false);
        }

        return $httpResponse;
    }

    /**
     * Builds the request.
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
            ->setParameters($request->getData() ?: [])
            ->setHeaders($this->getDefaultHeaders());
    }
}
