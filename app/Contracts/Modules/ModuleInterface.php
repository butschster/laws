<?php

namespace App\Contracts\Modules;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Kernel as ConsoleKernel;

interface ModuleInterface
{
    /**
     * Получение названия модуля
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Получение namespace для модуля
     *
     * @return string
     */
    public function getNamespace(): string;

    /**
     * Получение пути до файлов модуля
     *
     * @param string|null $path
     *
     * @return string
     */
    public function getPath(string $path = ''): string;

    /**
     * Получение namespace для контроллера
     *
     * @return string
     */
    public function getControllerNamespace(): string;

    /**
     * @param Schedule $schedule
     */
    public function schedule(Schedule $schedule);

    /**
     * @param ConsoleKernel $console
     */
    public function console(ConsoleKernel $console);
}
