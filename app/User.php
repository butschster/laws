<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Module\Billing\Entities\Invoice;
use Module\Billing\Entities\TransactionRobokassa;
use Module\Billing\Entities\Wallet;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        //TODO: Переделать под разные транзакции или вывести одну общую транзакцию как в Monetype
        return $this->hasMany(TransactionRobokassa::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompletedTransactionsAttribute()
    {
        return $this->transactions()->completed()->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Wallet::class);
    }
}
