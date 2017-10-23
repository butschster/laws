<?php

/**
 * Приведение даты к единому формату
 *
 * @param \Carbon\Carbon $date
 *
 * @return string
 */
function format_date(\Carbon\Carbon $date): string
{
    return $date->format('d.m.Y г.');
}

/**
 * Получение путя до модуля
 *
 * @param string $path
 *
 * @return string
 */
function modules_path(string $path = ''): string
{
    return app(App\Contracts\Modules\ManagerInterface::class)->basePath().($path ? DIRECTORY_SEPARATOR.$path : $path);
}