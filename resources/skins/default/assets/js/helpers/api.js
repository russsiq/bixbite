import axios from 'axios'
import store from '@/store';

function accessToken() {
    return {
        'Authorization': 'Bearer ' + store.getters['auth/api_token']
    };
}

export function get(url, params = {}) {
    return axios({
        url: url,
        params: params,
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            //'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        	...accessToken()
        }
    })
}

export function post(url, data) {
    return axios({
        url: url,
        data: data,
        method: 'POST',
        headers: {
        	...accessToken()
        }
    })
}

export function put(url, data) {
    return axios({
        url: url,
        data: data,
        method: 'PUT',
        headers: {
        	...accessToken()
        }
    })
}

export function update(url, data) {
    return axios({
        url: url,
        data: data,
        method: 'PUT',
        headers: {
        	...accessToken()
        }
    })
}

// NB! `delete` is reserved keyword.
export function destroy(url, params = {}) {
    return axios({
        url: url,
        params: params,
        method: 'DELETE',
        headers: {
            ...accessToken()
        }
    })
}
