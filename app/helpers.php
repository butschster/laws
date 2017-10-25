<?php

use Carbon\Carbon;

/**
 * Приведение даты к единому формату
 *
 * @param Carbon $date
 *
 * @return string
 */
function format_date(Carbon $date): string
{
    return $date->format('d.m.Y г.');
}

/**
 * @param string $date
 * @param string $format
 *
 * @return Carbon
 */
function custom_date(string $date, string $format = 'd.m.Y'): Carbon
{
    return Carbon::createFromFormat($format, $date);
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

/**
 * @param string $string
 *
 * @return string
 */
function toUtf8(string $string): string
{
    return iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8", $string);
}
