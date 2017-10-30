<?php

/** @var \Illuminate\Routing\Router $router */

$router
    ->post('claim-calculator', 'CalculatorController@calculate')
    ->name('claim-calculator');