<?php

namespace Module\Billing\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Billing\Entities\BalanceTransaction;
use Modules\Billing\Observers\BalanceTransactionObserver;

class BalanceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        BalanceTransaction::observe(BalanceTransactionObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
