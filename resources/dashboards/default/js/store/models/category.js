import Model from '@/store/model';

import Article from './article';
import Attachment from './attachment';

class Category extends Model {
    static entity = 'categories';

    static $attach({categoryable, category}, data, config = {}) {
        return this.api()
            .post(`categoryable/${categoryable.type}/${categoryable.id}/categories/${category.id}`, data, config);
    }

    static $detach({categoryable, category}, config = {}) {
        return this.api()
            .delete(`categoryable/${categoryable.type}/${categoryable.id}/categories/${category.id}`, config);
    }

    static $sync({categoryable}, data, config = {}) {
        return this.api()
            .put(`categoryable/${categoryable.type}/${categoryable.id}/categories`, data, config);
    }
}

export default Category;
