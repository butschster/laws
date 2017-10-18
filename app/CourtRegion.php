<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourtRegion extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Получение списка судов
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courts()
    {
        return $this->hasMany(Court::class);
    }
}
