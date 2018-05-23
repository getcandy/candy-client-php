<?php

namespace GetCandy\Client\Responses;

use CandyClient;

class SuccessResponse extends AbstractResponse
{
    public function __construct($response)
    {
        $this->meta = $this->normalize($response['meta']);
        $this->body = $this->normalize($response['data']);
    }

    protected function normalize($response)
    {
        // If it's multidimensional, make it a collection.

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
                }
            }
            $object->{$key} = $value;
        }
        return $object;
    }

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
