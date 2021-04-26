import axios from 'axios'
import store from '@/store';

export function get(url, params = {}) {
    return axios({
        url: url,
        params: params,
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
}

export function post(url, data) {
    return axios({
        url: url,
        data: data,
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
}

export function put(url, data) {
    return axios({
        url: url,
        data: data,
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
}

export function update(url, data) {
    return axios({
        url: url,
        data: data,
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
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
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
}
