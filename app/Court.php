<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Обновление статуса синхронизации
     *
     * @return void
     */
    public function synced()
    {
        $this->synced_at = now();
        $this->save();
    }

    /**
     * @param Builder $builder
     * @param int $days
     */
    public function scopeExpired(Builder $builder, int $days = 7)
    {
        $builder->where(function($builder) use($days) {
            $builder
                ->whereNull('synced_at')
                ->orwhere('synced_at', '<', now()->sub($days)->toDateString());
        });

    }
}
