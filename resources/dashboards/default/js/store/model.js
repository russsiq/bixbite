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

    static $fetch(config = {}) {
        return this.api()
            .get(this.entity, config);
    }

    static $get(id, config = {}) {
        return this.api()
            .get(`${this.entity}/${id}`, config);
    }

    static $create(data, config = {}) {
        return this.api()
            .post(this.entity, data, config);
    }

    static $update(id, data, config = {}) {
        return this.api()
            .put(`${this.entity}/${id}`, data, config);
    }

    static $massUpdate(ids, mass_action, config = {}) {
        return this.api()
            .put(this.entity, {
                [this.entity]: ids,
                mass_action: mass_action
            }, config);
    }

    static $delete(id, config = {}) {
        return this.api()
            .delete(`${this.entity}/${id}`, config);
    }
}
