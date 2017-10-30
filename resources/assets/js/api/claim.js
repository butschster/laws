/**
 * @return {AxiosPromise}
 */
export function calculate(params) {
    return axios.post(Url.route('claim-calculator'), params);
}