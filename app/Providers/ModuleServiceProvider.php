<?php

namespace App\Providers;

use App\Contracts\Modules\ManagerInterface as ModulesManagerContract;
use App\Contracts\Modules\ModuleInterface;
use Illuminate\Support\ServiceProvider;

abstract class ModuleServiceProvider extends ServiceProvider
{
    /**
     * @return ModulesManagerContract
     */
    public function getManager(): ModulesManagerContract
    {
        return $this->app[ModulesManagerContract::class];
    }

    /**
     * @param string $name
     * @param string|null $namespace
     *
     * @return ModuleInterface
     */
    public function registerModule(string $name, string $namespace = null): ModuleInterface
    {
        return $this->getManager()->make($name, $namespace);
    }
}
