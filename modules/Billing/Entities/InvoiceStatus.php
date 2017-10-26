<?php

namespace Module\Billing\Entities;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'invoice_statuses';

    /**
     * @var array
     */
    protected $guarded = [];


    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function invoices()
    {
    	return $this->hasMany(Invoice::class);
    }
}