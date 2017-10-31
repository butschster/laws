<?php

namespace Module\FineCalculator\Providers;

use App\Providers\ModuleServiceProvider;

class AppServiceProvider extends ModuleServiceProvider
{
    public function register()
    {
        $this->registerModule('FineCalculator', 'Module\FineCalculator');
    }
}