<?php

namespace App\Services\Kladr\Objects;

use Illuminate\Contracts\Support\Arrayable;

class District implements Arrayable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function id()
    {
        return array_get($this->data, 'id');
    }

    /**
     * @return string
     */
    public function type()
    {
        return array_get($this->data, 'type');
    }

    /**
     * @return string
     */
    public function typeShort()
    {
        return array_get($this->data, 'typeShort');
    }

    /**
     * @return string
     */
    public function name()
    {
        return array_get($this->data, 'name');
    }

    /**
     * @return string
     */
    public function okato()
    {
        return array_get($this->data, 'okato');
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}