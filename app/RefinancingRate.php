<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefinancingRate extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['rate', 'created_at'];

    /**
     * Set the value of the "updated at" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setUpdatedAt($value)
    {
        return $this;
    }
}
