/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add a response interceptor
axios.interceptors.response.use(response => {

    Bus.$emit('response.success', response.data);

    return response;
}, (error) => {
    if (!error.response) {
        return Promise.reject(error);
    }

    switch (error.response.status) {
        case 422:
            console.error('Validation', error.response.data);
            Bus.$emit('validation.thrown', error.response.data);
            break;

        case 401:
            console.error('Unauthorized', error.response.data);
            Bus.$emit('response.unauthorized', error.response.data);
            break;

        case 403:
            console.error('Access denied', error.response.data);
            Bus.$emit('response.access_denied', error.response.data);
            break;

        case 404:
            console.error('Page not found', error.response.data);
            Bus.$emit('response.not_found', error.response.data);
            break;

        default:
            console.error('Http error response', error.response);
    }

    return Promise.reject(error);
});

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


const router = require('../routes');
import UrlComponent from "../components/url";

window.Url = new UrlComponent(
    router,
    '/',
    'api'
);

import Api from '../api';
Vue.prototype.$api = window.Api = Api;