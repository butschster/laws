<?php
/**
 * @param \Carbon\Carbon $date
 *
 * @return string
 */
function format_date(\Carbon\Carbon $date): string
{
    return $date->format('d.m.Y г.');
}