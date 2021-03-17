import http from './axios-request-config';

export default class {

    static api() {
        return http;
    }

    static $fetch({ params }) {
        return this.api()
            .get(this.entity, {
                params,
                data: {},
                headers: {
                    ...params.headers,
                    'X-JSON-API-RESOURCE': this.entity,
                }
            });
    }

    static $get({ params }) {
        return this.api()
            .get(`${this.entity}/${params.id}`, {
                // params,
                data: {},
                headers: {
                    ...params.headers,
                    'X-JSON-API-RESOURCE': this.entity,
                }
            });
    }

    static $create({ params = {}, data }) {
        return this.api()
            .post(this.entity, data, {
                ...params.headers,
                headers: {
                    'X-JSON-API-RESOURCE': this.entity,
                }
            });
    }

    static $update({ params, data }) {
        return this.api()
            .put(`${this.entity}/${params.id}`, data, {
                headers: {
                    ...params.headers,
                    'X-JSON-API-RESOURCE': this.entity,
                }
            });
    }

    static $delete({ params }) {
        return this.api()
            .delete(`${this.entity}/${params.id}`, {
                data: {},
                headers: {
                    ...params.headers,
                    'X-JSON-API-RESOURCE': this.entity,
                }
            });
    }
}
