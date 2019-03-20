<?php

namespace GetCandy\Client\Responses;

use CandyClient;

class ApiResponse extends AbstractResponse
{
    protected $response;

    public function __construct(CandyHttpResponse $response)
    {
        $this->response = $response;
        if (! $this->wasSuccessful()) {
            $this->processErrorResponse();
        } else {
            $this->processSuccessResponse();
        }
    }

    /**
     * Processes a failed request.
     *
     * @return void
     */
    protected function processErrorResponse()
    {
        $this->failed = true;
        $this->body = $this->response->getData();
        $this->status = $this->response->getStatusCode();
    }

    /**
     * Processes a successful request.
     *
     * @return void
     */
    protected function processSuccessResponse()
    {
        $contents = $this->response->getData();
        $this->meta = $this->normalize($contents['meta'] ?? []);
        $this->body = $this->normalize($contents['data'] ?? []);
        $this->status = $this->response->getStatusCode();
    }

    /**
     * Determines whether the request was successful.
     *
     * @return bool
     */
    private function wasSuccessful()
    {
        return $this->response->fulfilled();
    }

    /**
     * Normalises our response data.
     *
     * @param array $response
     *
     * @return mixed
     */
    protected function normalize($response)
    {
        if ((isset($response[0]) && is_array($response[0])) || is_array($response) && ! count($response)) {
            return $this->mapCollection(
                collect($response)
            );
        }

        return $this->mapItem($response);
    }

    /**
     * Maps a response collection.
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
     * Maps an item in the response.
     *
     * @param mixed $item
     *
     * @return ResponseObject
     */
    protected function mapItem($item)
    {
        $object = new ResponseObject();

        foreach ($item ?: [] as $key => $value) {
            if ($key == 'attribute_data') {
                $attributes = $this->getMappedAttributes($value);
                foreach ($attributes as $attribute => $body) {
                    $object->{$attribute} = $body;
                }
                continue;
            }
            if (is_array($value) || $value instanceof \Illuminate\Database\Eloquent\Collection) {
                if (! empty($value['data'])) {
                    if (isset($value['data'][0])) {
                        $value = $this->mapCollection($value['data']);
                    } else {
                        $value = $this->mapItem($value['data']);
                    }
                } elseif (isset($value[0]) || ! count($value)) {
                    $value = $this->mapCollection($value);
                } elseif (isset($value['data']) && is_iterable($value['data'])) {
                    $value = $this->mapCollection($value['data']);
                } elseif (array_key_exists('data', $value) && is_null($value['data'])) {
                    $value = $value['data'];
                }
            }
            $object->{$key} = $value;
        }

        return $object;
    }

    /**
     * Gets mapped attrbutes based on locale/environment.
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

    protected function isHtml($content)
    {
        return preg_match('/<[^<]+>/', $content, $m) != 0;
    }
}
