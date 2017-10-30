<?php

namespace Module\Billing\Entities;

use Illuminate\Database\Eloquent\Model;
use Module\Billing\Exceptions\WrongInvoiceStatusException;

class Invoice extends Model
{
    /**
     * @var string
     */
    protected $table = 'invoices';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $amount
     * @param \Module\Billing\Entities\Wallet $wallet
     * @return static
     */
    public static function createForWallet($amount, Wallet $wallet)
    {
        $invoice = new static();

        $invoice->amount = $amount;
        $invoice->wallet()->associate($wallet);
        $invoice->fillStatus('new');

        $invoice->save();

        return $invoice;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * @param string|InvoiceStatus $status
     * @return $this
     */
    public function fillStatus($status)
    {
        $status = $status instanceof InvoiceStatus ? $status : InvoiceStatus::where('code', $status)->firstOrFail();

        $this->status()->associate($status);

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class);
    }

    public function pay()
    {
        $this->checkStatusForPay();

        $this->fillStatus('completed');

        $this->wallet->deposit($this->amount);

        return $this->save();
    }

    protected function checkStatusForPay()
    {
        if ($this->status->code == 'completed') {
            throw new WrongInvoiceStatusException();
        }

        return $this;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNew($query)
    {
        //TODO: Отрефакторить бы получение ID статуса
        return $query->where('status_id', InvoiceStatus::where('code', InvoiceStatus::STATUS_NEW)->first()->id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCanceled($query)
    {
        //TODO: Отрефакторить бы получение ID статуса
        return $query->where('status_id', InvoiceStatus::where('code', InvoiceStatus::STATUS_CANCELED)->first()->id);
    }
}