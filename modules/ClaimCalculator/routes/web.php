<?php

/** @var \Illuminate\Routing\Router $router */

$router->get('claim/calculator', 'CalculatorController@index')->name('claim.calculator.fine');
$router->post('claim/calculate', 'CalculatorController@calculate')->name('claim.calculate.fine');