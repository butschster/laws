/**
 * @return {AxiosPromise}
 */
export function me(params) {
    return axios.get(Url.route('me'), {params});
}