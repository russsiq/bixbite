import store from '@/store';
import http from '@/store/axios-request-config';

// let query = this.search.query
// let where = this.search.where
// let filter = new RegExp(query, 'i')
//
// if (['title', 'description'].includes(where)) {
//     return this.articles.filter(article => article[where].match(filter))
// }

export default class {

    static api() {
        return http;
    }

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
            });
    }

    static $get({params}) {
        return this.api()
            .get(`${this.entity}/${params.id}`, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
            });
    }

    static $create({data}) {
        return this.api()
            .post(this.entity, data, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
            });
    }

    static $update({params, data}) {
        const id = params.id;

        return this.api()
            .put(this.entity+'/'+id, data, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
            });
    }

    static $massUpdate(ids, mass_action) {
        return this.api()
            .put(this.entity, {
                [this.entity]: ids,
                mass_action: mass_action
            }, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
            });
    }

    static $delete({params}) {
        return this.api()
            .delete(`${this.entity}/${params.id}`, {
                headers: {
                    'Authorization': 'Bearer ' + this.apiToken(),
                },
            });
    }
}
