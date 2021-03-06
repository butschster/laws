let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .autoload({
        'socket.io-client': ['io', 'window.io'],
        'popper.js': ['Popper', 'window.Popper']
    })
    .js('resources/assets/js/app.js', 'public/js')
    .extract(['vue', 'lodash', 'axios', 'jquery', 'socket.io-client', 'popper.js'], 'public/js/vendors.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .version();