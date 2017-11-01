/**
 * @return {AxiosPromise}
 */
export function calculate(params) {
    return axios.post(Url.route('claim.calculate.fine'), params);
}

/**
 * @return {AxiosPromise}
 */
export function calculate395(params) {
    return axios.post(Url.route('claim.calculate.395'), params);
}

export function generateDocument(params) {
    return axios.post(Url.route('claim.send'), params);
}