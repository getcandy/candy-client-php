<?php

namespace GetCandy\Client\Responses;

use Illuminate\Support\Collection;

class ResponseObject
{
    protected $attributes = [];

    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function toArray()
    {
        $data = [];

        foreach ($this->attributes as $key => $value) {
            if (is_object($value)) {
                if ($value instanceof Collection) {
                    $collection = [];
                    foreach ($this->getAttribute($key) as $obj) {
                        $collection[] = $obj->toArray();
                    }
                    $value = $collection;
                } else {
                    $value = $this->getAttribute($key)->toArray();
                }
            }
            $data[$key] = $value;
        }
        return $data;
    }
}
