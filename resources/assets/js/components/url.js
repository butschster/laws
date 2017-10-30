module.exports = class Url {

    /**
     * @param {String} url
     * @param {String} api_prefix
     */
    constructor(router, url, api_prefix) {
        this._router = router;
        this._url = _.trimEnd(url, '/');
        this._api_prefix = api_prefix;
    }

    /**
     * Получение якоря
     *
     * @returns {bool}
     */
    get hasHash () {
        return !!window.location.hash;
    }

    /**
     * Получение якоря
     *
     * @returns {string}
     */
    get hash () {
        return this.hasHash ? window.location.hash.substr(1) : '';
    }

    /**
     * @param {String} path
     */
    set hash(path) {
        if(_.isString(path) && path.length > 0)
            window.history.pushState({}, document.title, path);
        else
            window.history.pushState({}, document.title, window.location.pathname);
    }

    /**
     * Ссылка на front
     *
     * @returns {String}
     */
    get url() {
        return this._url;
    }

    set url(value) {
        throw new Error(`The url property cannot be written.`);
    }

    /**
     *
     * @param {String} name
     * @param {Object} params
     */
    route(name, params) {
        let url = this._router.route(name, params);

        if (!url) {
            throw new Error(`Route [${name}] not found.`);
        }

        return url;
    }

    /**
     * Генерация api ссылки
     *
     * @param {String} path относительный путь
     * @param {Object} query (Опционально) параметры для генерации query string {foo: bar, baz: bar} = ?foo=bar&baz=bar
     * @returns {String}
     */
    api(path, query) {
        path = _.trimStart(path, '/');

        return this.app(
            `${this._api_prefix}/${path}`,
            query
        );
    }

    /**
     * Генерация front ссылки
     *
     * @param {String} path относительный путь
     * @param {Object} query (Опционально) параметры для генерации query string {foo: bar, baz: bar} = ?foo=bar&baz=bar
     * @returns {String}
     */
    app(path, query) {
        path = _.trimStart(path, '/');

        return this._buildUrl(
            `${this._url}/${path}`,
            query
        );
    }

    _buildUrl(url, query) {
        if(_.isObject(query)) {
            query = this._serialize(query);

            if (query.length) {
                url += `?${query}`;
            }
        }

        return url;
    }

    _serialize (query) {
        let str = [];
        for (let p in query)
            if (query.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(query[p]));
            }

        return str.join("&");
    }
}