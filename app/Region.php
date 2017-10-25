<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['name', 'federal_district_id'];

    /**
     * Получение списка судов
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courts()
    {
        return $this->hasMany(Court::class);
    }

    /**
     * Получение федерального округа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function federalDistrict()
    {
        return $this->belongsTo(FederalDistrict::class);
    }
}
