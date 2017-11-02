<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{

    const TYPE_COMMON        = 'fs'; // суды РФ общей юрисдикции
    const TYPE_MIR           = 'mir'; // мировые суда РФ
    const TYPE_ARBITR_OKRUG  = 'okrug'; // Арбитражные суды округов
    const TYPE_ARBITR_APPEAL = 'appeal'; // Арбитражные апелляционные суды
    const TYPE_ARBITR_SUBJ   = 'subj'; // Арбитражные суды субъектов РФ
    const TYPE_ARBITR_SIP    = 'sip'; // Суд по интеллектуальным правам

    /**
     * Получение списка типов судов
     *
     * @return array
     */
    public static function types()
    {
        return [
            static::TYPE_COMMON => 'Суд общей юрисдикции',
            static::TYPE_MIR => 'Мировой суд',
        ];
    }

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
        'email' => 'array',
    ];

    /**
     * Получение списка подсудностей
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jurisdictions()
    {
        return $this->hasMany(CourtJurisdiction::class);
    }

    /**
     * Получение региона
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Получение федерального округа
     *
     * @return FederalDistrict
     */
    public function federalDistrict()
    {
        return $this->region->federalDistrict;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function kladr()
    {
        return $this->hasOne(CourtKladr::class);
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
     * Получение списка судов с устаревшимим данными (для синхронизации)
     *
     * @param Builder $builder
     * @param int $days
     */
    public function scopeExpired(Builder $builder, int $days = 7)
    {
        $builder->where(function ($builder) use ($days) {
            $builder->whereNull('synced_at')->orwhere('synced_at', '<', now()->subDays($days)->toDateString());
        });
    }
}
