<?php

/** @var \Illuminate\Routing\Router $router */

$router
    ->post('fine-calculator', 'CalculatorController@calculate')
    ->name('fine-calculator');