module.exports = (function () {

    return {
        /**
         *
         * @param {String} path
         * @returns {String}
         */
        path (path) {
            if (path.charAt(0) === '/')
                path = path.slice(1);

            return window.settings.asset_url + path;
        },

        /**
         *
         * @param {String} path
         * @returns {String}
         */
        api (path) {
            if (path.charAt(0) === '/')
                path = path.slice(1);

            return this.path('api/' + path);
        },

        /**
         *
         * @param {Object} assets
         */
        register (assets) {
            let self = this;
            if (_.isObject(assets)) {
                _.forEach(assets, function (value, key) {
                    self[key](value);
                });
            }
        },

        /**
         * Подключение JS файла. Если файл уже добавлен, вернет false
         *
         * @param {String} url
         * @return {bool, Promise}
         */
        js (url) {

            return new Promise(function (resolve, reject) {

                let len = $('script').filter(function () {
                    return ($(this).attr('src') == url);
                }).length;

                if (len > 0) {
                    console.log(`Script file ${url} is loaded.`, 'Asset');
                    return;
                }

                let script = document.createElement('script');

                script.onload = function () {
                    resolve(url);
                };
                script.onerror = function () {
                    reject(url);
                };
                script.src = url;

                document.getElementsByTagName('head')[0].appendChild(script);
            });

        },
        /**
         * Подключение CSS файла. Если файл уже добавлен, вернет false
         *
         * @param {String} url
         * @return {Promise}
         */
        css (url) {

            return new Promise(function (resolve, reject) {

                let ss = document.styleSheets;
                for (let i = 0, max = ss.length; i < max; i++) {
                    if (ss[i].href == url)
                        console.log(`CSS file ${url} is loaded.`, 'Asset');
                    return;
                }

                let link = document.createElement('link');

                link.onload = function () {
                    resolve(url);
                };

                link.onerror = function () {
                    reject(url);
                };

                link.href = url;
                link.type = 'text/css';
                link.rel = 'stylesheet';

                document.getElementsByTagName('head')[0].appendChild(link);
            });

        },

        /**
         * Подключение image файла. Если файл уже добавлен, вернет false
         *
         * @param {String} url
         * @return {bool, Promise}
         */
        img (url) {

            return new Promise(function (resolve, reject) {

                let img = new Image();

                img.onload = function () {
                    resolve(url);
                };
                img.onerror = function () {
                    reject(url);
                };

                img.src = url;
            });

        }

    };
})();