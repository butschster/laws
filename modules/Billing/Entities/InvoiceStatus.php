<?php

namespace Module\Billing\Entities;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    const STATUS_NEW = 'new'; // Проведена

    const STATUS_COMPLETED = 'completed'; // Проведена

    const STATUS_CANCELED = 'canceled'; // Отклонено

    const STATUS_PROCESSING = 'processing'; // В процессе

    const STATUS_FAILED = 'failed'; // Не проведен

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