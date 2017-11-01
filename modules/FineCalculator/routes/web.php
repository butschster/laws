<?php

/** @var \Illuminate\Routing\Router $router */

$router->get('claim/395/calculator', 'CalculatorController@index')->name('claim.calculator.395');
$router->post('claim/395/calculate', 'CalculatorController@calculate')->name('claim.calculate.395');