<?php

namespace GetCandy\Client\Responses;

use ArrayAccess;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

class ResponseObject implements Arrayable, ArrayAccess
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

    public function getAttributes()
    {
        return $this->attributes;
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

        /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ! is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset], $this->relations[$offset]);
    }
}
