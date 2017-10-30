<?php

namespace Module\Billing\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TransactionRobokassa extends Model
{
    const STATUS_NEW = 'new'; // Новая
    const STATUS_COMPLETED = 'completed'; // Проведена
    const STATUS_CANCELED = 'canceled'; // Отклонено

    /**
     * @var string
     */
    protected $table = 'transactions_robokassa';

    /**
     * @var array
     */
    protected $guarded = [];

    protected $paymentMethod = 'robokassa';

    public static function getAvailabelStatuses()
    {
        return [
            static::STATUS_NEW => 'new',
            static::STATUS_COMPLETED => 'completed',
            static::STATUS_CANCELED => 'canceled',
        ];
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->status == static::STATUS_NEW;
    }


    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status == static::STATUS_COMPLETED;
    }


    /**
     * @return bool
     */
    public function isCanceled()
    {
        return $this->status == static::STATUS_CANCELED;
    }

    /**
     * @return bool
     */
    public function complete()
    {
        if (!$this->isNew()) {
            return false;
        }

        $this->invoice->complete(function () {
            $this->status = static::STATUS_COMPLETED;
            $this->save();
        });

        return true;
    }


    /**
     * @return bool
     */
    public function cancel()
    {
        if ($this->isCanceled()) {
            return false;
        }

        $this->invoice->cancel(function () {
            $this->status = static::STATUS_CANCELED;
            $this->save();
        });

        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', static::STATUS_COMPLETED);
    }
}