<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CourtKladr extends Model
{
    /**
     * @var string
     */
    protected $table = 'kladr_court';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}