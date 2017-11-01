<?php

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Auth::routes();

Route::get('home', 'HomeController@index')->name('home');
Route::get('claim', 'ClaimController@index')->name('claim');
Route::post('claim', 'ClaimController@send')->name('claim.send');
