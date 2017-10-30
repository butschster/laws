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

    public static function getAvailableStatuses()
    {
        return [
            static::STATUS_NEW => 'new',
            static::STATUS_COMPLETED => 'completed',
            static::STATUS_CANCELED => 'canceled',
            static::STATUS_PROCESSING => 'processing',
            static::STATUS_FAILED => 'failed',
        ];
    }

    public static function convertStatusToTransationStatus(string $status)
    {
        $statuses = [
            static::STATUS_NEW => TransactionRobokassa::STATUS_NEW,
            static::STATUS_COMPLETED => TransactionRobokassa::STATUS_COMPLETED,
            static::STATUS_CANCELED => TransactionRobokassa::STATUS_CANCELED,
            static::STATUS_PROCESSING => TransactionRobokassa::STATUS_NEW,
            static::STATUS_FAILED => TransactionRobokassa::STATUS_CANCELED,
        ];

        return $statuses[$status];
    }


    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function invoices()
    {
    	return $this->hasMany(Invoice::class);
    }
}