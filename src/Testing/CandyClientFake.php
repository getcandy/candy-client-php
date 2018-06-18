<?php

namespace GetCandy\Client\Testing;

use GuzzleHttp\Client;
use GetCandy\Client\Candy;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;

class CandyClientFake extends Candy
{
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
        $this->clientSecret = 'foobar';
        $this->clientId = 69;
        $this->locale = 'en';
        $this->channel = 'webstore';
    }

    /**
     * Set the client for the next request
     *
     * @param mixed $response
     * @param callable $callable
     * @return void
     */
    public function next($response, $callable = null)
    {
        if ($callable && is_callable($callable)) {
            $response = new Response($response, $callable());
        } elseif (!$response instanceof Response) {
            abort(400);
        }
        $this->client = new Client([
            'handler' => $this->getStack($response)
        ]);
    }

    /**
     * Queue up responses
     *
     * @param array $responses
     * @return void
     */
    public function queue(array $responses)
    {
        $this->client = new Client([
            'handler' => $this->getStack($responses)
        ]);
    }

    /**
     * Get the handler stack
     *
     * @param mixed $responses
     * @return HandlerStack
     */
    protected function getStack($responses)
    {
        return HandlerStack::create(
            $this->getHandler($responses)
        );
    }

    /**
     * Get the call stack handler
     *
     * @param int $status
     * @param array $body
     * @param array $headers
     * @return MockHandler
     */
    protected function getHandler($responses)
    {
        $payload = [];

        if (is_array($responses)) {
            foreach ($responses as $response) {
                $payload[] = $response->getMock();
            }
        } else {
            $payload[] = $responses->getMock();
        }

        return new MockHandler($payload);
    }

    /**
     * Get the access token
     *
     * @param boolean $force
     * @return string
     */
    public function getToken($force = false)
    {
        return '12345678910';
    }
}
