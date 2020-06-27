import {
    Model
} from '@vuex-orm/core';

import store from '@/store';

export default class extends Model {
    static apiToken() {
        return store.getters['auth/api_token'];
    }

    static $fetch(params) {
        return this.api()
            .get(this.entity, {
                params,
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
                dataKey: 'data'
            });
    }

    static async $get({params}) {
        const id = params.id;

        const response = await this.api()
            .get(this.entity+'/'+id, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
                dataKey: 'data',
            });

        return this.query().whereId(id).withAll().first();
    }

    static async $create({data}) {
        const response = await this.api()
            .post(this.entity, data, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
                dataKey: 'data',
            });

        const id = response.entities[this.entity][0].id;

        return this.query().whereId(id).withAll().first();
        return this.query().last();
    }

    static async $update({params, data}) {
        const id = params.id;

        const response = await this.api()
            .put(this.entity+'/'+id, data, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
                dataKey: 'data',
            });

        return this.query().whereId(id).withAll().first();
    }

    static $delete({params}) {
        const id = params.id;

        return this.api()
            .delete(this.entity+'/'+id, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
                delete: id
            });
    }
}
