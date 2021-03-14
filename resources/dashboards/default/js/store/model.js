import http from '@/store/axios-request-config';

export default class {

    static api() {
        return http;
    }

    static $fetch(params) {
        return this.api()
            .get(this.entity, {
                params,
                data: {},
            });
    }

    static $get({ params }) {
        return this.api()
            .get(`${this.entity}/${params.id}`);
    }

    static $create({ data }) {
        return this.api()
            .post(this.entity, data);
    }

    static $update({ params, data }) {
        return this.api()
            .put(`${this.entity}/${params.id}`, data);
    }

    static $delete({ params }) {
        return this.api()
            .delete(`${this.entity}/${params.id}`);
    }
}
