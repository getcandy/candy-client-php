<?php

namespace GetCandy\Client\Responses;

use CandyClient;

class SuccessResponse extends AbstractResponse
{
    public function __construct($response)
    {
        $this->meta = $response['meta'];
        $this->body = $this->normalize($response);
    }

    protected function normalize($response)
    {
        // If it's multidimensional, make it a collection.
        $data = $response['data'];

        if (isset($data[0]) && is_array($data[0])) {
            return $this->mapCollection(
                collect($data)
            );
        }
        return $this->mapItem($data);
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
