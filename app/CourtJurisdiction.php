<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourtJurisdiction extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = ['city', 'address'];
}
