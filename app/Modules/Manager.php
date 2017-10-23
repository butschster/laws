<?php

namespace App\Modules;

use App\Console\Kernel as ConsoleKernel;
use App\Contracts\Modules\ManagerInterface;
use App\Contracts\Modules\ModuleInterface;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Illuminate\Console\Application as Artisan;

class Manager implements ManagerInterface
{
    /**
     * @var ModulesCollection
     */
    protected $modules;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->modules = new ModulesCollection();
        $this->app = $application;
    }

    /**
     * @return string
     */
    public function basePath(): string
    {
        return $this->app->basePath().DIRECTORY_SEPARATOR.'modules';
    }

    /**
     * @param string $name
     * @param string|null $namespace
     *
     * @return ModuleInterface
     */
    public function make(string $name, string $namespace = null): ModuleInterface
    {
        return $this->register(
            new DefaultModule($this->app, $name, $namespace)
        );
    }

    /**
     * @param ModuleInterface $module
     *
     * @return ModuleInterface
     */
    public function register(ModuleInterface $module): ModuleInterface
    {
        $this->modules->push($module);
        $this->app->register($module, [], true);

        return $module;
    }

    /**
     * @param Schedule $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        $this->modules->each(function (ModuleInterface $module) use ($schedule) {
            $module->schedule($schedule);
        });
    }

    /**
     * @return ModulesCollection|Module[]
     */
    public function getModules(): ModulesCollection
    {
        return $this->modules;
    }

    /**
     * @param ConsoleKernel $console
     *
     * @return mixed
     */
    public function console(ConsoleKernel $console)
    {
        $this->modules
            ->each(function (ModuleInterface $module) use ($console) {
                $this->loadCommands($module);
            })
            ->each(function (ModuleInterface $module) use ($console) {
                $module->console($console);
            });
    }

    /**
     * @param ModuleInterface $module
     */
    protected function loadCommands(ModuleInterface $module)
    {
        $paths = array_filter([$module->getPath('Console/Commands')], function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        $namespace = $module->getNamespace();

        foreach ((new Finder())->in($paths)->files() as $command) {
            $command = $namespace.str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($command->getPathname(), $module->getPath())
            );

            if (is_subclass_of($command, Command::class) &&
                ! (new \ReflectionClass($command))->isAbstract()) {

                Artisan::starting(function ($artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }
}
