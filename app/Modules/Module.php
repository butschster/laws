<?php

namespace App\Modules;

use App\Contracts\Modules\ModuleInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Route;

abstract class Module extends ServiceProvider implements ModuleInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $controllerNamespace;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param Application $application
     * @param string $name
     * @param string|null $namespace
     */
    public function __construct(Application $application, string $name, string $namespace = null)
    {
        parent::__construct($application);

        $this->name = $name;
        $this->namespace = ($namespace ?: studly_case($name));
        $this->controllerNamespace = ($namespace ?: studly_case($name)).'\Http\Controllers';
    }

    /**
     * Получение названия модуля
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Получение пути для файлов модуля
     *
     * @param string|null $path
     *
     * @return string
     */
    public function getPath(string $path = ''): string
    {
        return modules_path($this->getName().($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    /**
     * Получение namespace для модуля
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Получение namespace для контроллера
     *
     * @return string
     */
    public function getControllerNamespace(): string
    {
        return $this->controllerNamespace;
    }

    /**
     * Register any module services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any module services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadTranslationsFrom(modules_path("{$this->name}/resources/lang"), $this->name);
        $this->loadViewsFrom(modules_path("{$this->name}/resources/views"), $this->name);

        $this->mapRoutes();
    }

    /**
     * Define the routes for the module.
     *
     * @return void
     */
    protected function mapRoutes()
    {
        if (! $this->app->routesAreCached()) {

            $this->mapApiRoutes();
            $this->mapWebRoutes();

            //

        }
    }

    /**
     * Define the "api" routes for the module.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        if (is_file($routesFilePath = modules_path("{$this->name}/routes/api.php"))) {

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->controllerNamespace)
                ->group($routesFilePath);

        }
    }

    /**
     * Define the "web" routes for the module.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        if (is_file($routesFilePath = modules_path("{$this->name}/routes/web.php"))) {

            Route::middleware('web')
                 ->namespace($this->controllerNamespace)
                 ->group($routesFilePath);

        }
    }
}
