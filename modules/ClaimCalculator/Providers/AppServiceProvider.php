<?php

namespace Module\ClaimCalculator\Providers;

use App\Providers\ModuleServiceProvider;

class AppServiceProvider extends ModuleServiceProvider
{

    public function register()
    {
        $this->registerModule('ClaimCalculator', 'Module\ClaimCalculator');
    }
}