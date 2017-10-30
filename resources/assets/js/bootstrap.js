/**
 * A modern JavaScript utility library delivering modularity, performance & extras.
 */
window._ = require('lodash');

/**
 * JS Storage is a plugin that simplifies access to storages (HTML5), cookies,
 * and namespace storage functionality and provides compatiblity for old
 * browsers with cookies!
 */

window.Storages = require('js-storage');
window.s = Storages.localStorage;

/**npm run
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
    require('./unify');

} catch (e) {
    console.error('jQuery components not inited!', e)
}

/**
 * We'll load the Moment JS for parse, validate, manipulate, and display dates and
 * times in JavaScript.
 */
window.moment = require('moment');
moment.locale('ru');

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
window.Vue = require('vue');
window.Bus = new Vue();

require('./bootstrap/http');

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.settings.config.websocket.host
// });

require('./libs/number');