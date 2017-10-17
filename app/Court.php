<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [''];

    /**
     * @var array
     */
    protected $casts = [
        'email' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jurisdictions()
    {
        return $this->hasMany(CourtJurisdiction::class);
    }
}
