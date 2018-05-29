<?php

namespace GetCandy\Client\Responses;

use CandyClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;

class ApiResponse extends AbstractResponse
{
    protected $states = [
        'rejected' => 0,
        'fulfilled' => 1
    ];

    protected $response;

    public function __construct($response)
    {

        $this->response = $response;

        if (!$this->wasSuccessful()) {
            $this->processErrorResponse();
        } else {
            $this->processSuccessResponse();
        }
    }

    /**
     * Processes a failed request
     *
     * @return void
     */
    protected function processErrorResponse()
    {
        if ($this->response instanceof Response) {
            $reason = $this->response;
        } else {
            $reason = $this->response['reason'];
        }

        $this->failed = true;

        if ($reason instanceof ClientException) {
            $contents = json_decode($reason->getResponse()->getBody()->getContents(), true);
            $this->body = $contents;
            $this->status = $reason->getResponse()->getStatusCode();
            $this->meta = $reason->getTrace();
        } elseif ($reason instanceof ServerException) {
            $contents = json_decode($reason->getResponse()->getBody()->getContents(), true);
            $this->body = $contents;
            $this->status = $reason->getResponse()->getStatusCode();
            $this->meta = $reason->getTrace();
        } elseif ($reason instanceof Response) {
            $this->body =json_decode($reason->getBody()->getContents(), true);
            $this->status = $reason->getStatusCode();
        } else {
            $this->status = $response['error']['http_code'];
            $this->body = $response['error']['message'];
        }
    }

    /**
     * Processes a successful request
     *
     * @return void
     */
    protected function processSuccessResponse()
    {
        if ($this->response instanceof Response) {
            $this->body = $this->normalize(json_decode($this->response->getBody()->getContents(), true));
        } else {
            $contents = json_decode($this->response['value']->getBody()->getContents(), true);
            $this->meta = $this->normalize($contents['meta']);
            $this->body = $this->normalize($contents['data']);
        }
    }

    /**
     * Determines whether the request was successful
     *
     * @return boolean
     */
    private function wasSuccessful()
    {
        if ($this->response instanceof Response) {
            return $this->response->getStatusCode() == 200;
        }
        return isset($this->states[$this->response['state']]) ?
            (bool) $this->states[$this->response['state']] :
            false;
    }

    /**
     * Normalises our response data
     *
     * @param array $response
     *
     * @return mixed
     */
    protected function normalize($response)
    {
        if (isset($response[0]) && is_array($response[0])) {
            return $this->mapCollection(
                collect($response)
            );
        }
        return $this->mapItem($response);
    }

    /**
     * Maps a response collection
     *
     * @param mixed $items
     *
     * @return Collection
     */
    protected function mapCollection($items)
    {
        $collection = collect();

        foreach ($items as $item) {
            $collection->push($this->mapItem($item));
        }

        return $collection;
    }

    /**
     * Maps an item in the response
     *
     * @param mixed $item
     *
     * @return ResponseObject
     */
    protected function mapItem($item)
    {
        $object = new ResponseObject();
        foreach ($item as $key => $value) {
            if ($key == 'attribute_data') {
                $attributes = $this->getMappedAttributes($value);
                foreach ($attributes as $attribute => $body) {
                    $object->{$attribute} = $body;
                }
                continue;
            }

            if (is_array($value) || $value instanceof \Illuminate\Database\Eloquent\Collection) {
                if (!empty($value['data'])) {
                    if (isset($value['data'][0])) {
                        $value = $this->mapCollection($value['data']);
                    } else {
                        $value = $this->mapItem($value['data']);
                    }
                } elseif (isset($value[0]) || !count($value)) {
                    $value = $this->mapCollection($value);
                } elseif (isset($value['data']) && !count($value['data'])) {
                    $value = $this->mapCollection($value['data']);
                }
            }
            $object->{$key} = $value;
        }
        return $object;
    }

    /**
     * Gets mapped attrbutes based on locale/environment
     *
     * @param array $attributes
     *
     * @return mixed
     */
    protected function getMappedAttributes($attributes)
    {
        $data = [];

        $channel = CandyClient::getChannel();
        $locale = CandyClient::getLocale();

        foreach ($attributes as $key => $attribute) {
            if (empty($attribute[$channel])) {
                $val = array_first($attribute);
            } else {
                $val = $attribute[$channel];
            }
            if (empty($val[$locale])) {
                $val = array_first($val);
            } else {
                $val = $val[$locale];
            }
            $data[$key] = $val;
        }

        return $data;
    }
}
