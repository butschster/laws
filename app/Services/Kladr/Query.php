<?php

namespace App\Services\Kladr;

use Illuminate\Contracts\Support\Arrayable;

abstract class Query implements Arrayable
{
    /**
     * @var string
     */
    protected $parentType;

    /**
     * @var string
     */
    protected $parentId;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var bool
     */
    protected $oneString;

    /**
     * @var bool
     */
    protected $withParent;

    /**
     * @var string
     */
    protected $limit;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $query = [];

        if ($this->parentType && $this->parentId) {
            $query['Id'] = $this->parentId;
        }

        if ($this->query) {
            $query['query'] = $this->query;
        }

        if ($this->contentType) {
            $query['contentType'] = $this->contentType;
        }

        if ($this->zip) {
            $query['zip'] = $this->zip;
        }

        if ($this->oneString) {
            $query['oneString'] = 1;
        }

        if ($this->withParent) {
            $query['withParent'] = 1;
        }

        if ($this->limit) {
            $query['limit'] = $this->limit;
        }

        return $query;
    }
}