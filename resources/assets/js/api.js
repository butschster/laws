import * as user from './api/user';
import * as claim from './api/claim';

export default (function () {

    const methods = {
        user,
        claim
    };

    class ApiMethods {
        constructor(methods) {
            for (let prop in methods) {
                this[prop] = (...params) => {
                    if (typeof methods[prop] == 'function') {
                        return methods[prop](...params);
                    }

                    return methods[prop];
                }
            }
        }
    }

    class Api {
        register(module, methods) {
            this[module] = () => {
                return new ApiMethods(methods);
            }
        }
    }

    let response = new Api();

    for (let prop in methods) {
        response.register(prop, methods[prop]);
    }

    return new Proxy(response, {
        get(target, property) {
            if (typeof target[property] === 'undefined') {
                throw new Error('Api method not found');
            }

            if (property == 'register') {
                return target[property];
            }

            return target[property]();
        },

        set(target, property, value, receiver) {

            if (typeof target[property] === 'undefined') {
                target[property] = value;
                return true;
            }

            if (typeof target[property]() === 'object') {
                throw new Error('You can\'t override api methods');
            }

            return false;
        }
    });
})()