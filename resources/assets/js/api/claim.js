/**
 * @return {AxiosPromise}
 */
export function calculate(params) {
    return axios.post(Url.route('claim-calculator'), params);
}

/**
 * @return {AxiosPromise}
 */
export function calculate395(params) {
    return axios.post(Url.route('fine-calculator'), params);
}