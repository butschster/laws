<?php

namespace Module\Billing\Providers;

use App\Providers\ModuleServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;

class AppServiceProvider extends ModuleServiceProvider
{

    public function register()
    {
        $this->registerModule('billing', 'Module\Billing');
        $this->app->register(EventServiceProvider::class);
        $this->app->register(BalanceServiceProvider::class);
    }

    /**
     * @param Gate $gate
     */
    public function boot(Gate $gate)
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->registerPolicies($gate);
    }

    /**
     * @param Gate $gate
     */
    protected function registerPolicies(Gate $gate)
    {
        //$gate->policy(Entity::class, EntityPolicy::class);
    }
}