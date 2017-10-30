<?php

/** @var Illuminate\Routing\Router $router */

$router->group([], function ($router) {

    $router->get('/profile/wallet', 'Profile\WalletController@index')->name('profile.wallet')->middleware(['auth']);

});
