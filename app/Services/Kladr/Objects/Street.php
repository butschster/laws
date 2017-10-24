<?php

namespace App\Services\Kladr\Objects;

use App\Services\Kladr\ObjectType;
use Illuminate\Contracts\Support\Arrayable;

class Street implements Arrayable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var City
     */
    private $city;

    /**
     * @var Region
     */
    private $region;

    /**
     * @var District
     */
    private $district;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        $parents = array_get($this->data, 'parents', []);

        $this->city = new City(array_first($parents, function (array $parent) {
            return array_get($parent, 'contentType') == ObjectType::CITY;
        }, []));

        $this->region = new Region(array_first($parents, function (array $parent) {
            return array_get($parent, 'contentType') == ObjectType::REGION;
        }, []));

        $this->district = new District(array_first($parents, function (array $parent) {
            return array_get($parent, 'contentType') == ObjectType::DISTRICT;
        }, []));
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
    public function zip()
    {
        return array_get($this->data, 'zip');
    }

    /**
     * @return string
     */
    public function fullName()
    {
        return array_get($this->data, 'fullName');
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
        unset($this->data['parents']);

        return $this->data + [
                'city' => $this->city->toArray(),
                'region' => $this->region->toArray(),
                'district' => $this->district->toArray(),
            ];
    }
}