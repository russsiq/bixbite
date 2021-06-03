import Model from '@/store/model';

import Article from './article';
import Attachment from './attachment';

class Category extends Model {
    static fields() {
        return {
            id: this.attr(null),
            image_id: this.number().nullable(),
            parent_id: this.number(0),
            position: this.number().nullable(),

            //depth: this.number(0),

            title: this.string().nullable(),
            slug: this.string().nullable(),
            alt_url: this.string().nullable(),
            description: this.string().nullable(),
            keywords: this.string().nullable(),
            info: this.string().nullable(),

            show_in_menu: this.boolean(true),
            paginate: this.number().nullable(),
            order_by: this.string().nullable(),
            direction: this.string().nullable(),

            template: this.string().nullable(),

            // Связи с другими сущностями.
            //children: this.attr(null),
            //children: this.hasMany(Category, 'parent_id', 'id'),
            articles: this.morphedByMany(Article, Categoryable, 'category_id', 'categoryable_id', 'categoryable_type'),
            attachments: this.morphMany(Attachment, 'attachable_id', 'attachable_type'),
            image: this.hasOne(Attachment, 'id', 'image_id'),

            // Счетчики для других сущностей.
            articles_count: this.number(0),
            attachments_count: this.number(0),

            // Appends attributes.
            url: this.attr(null),

            // Временные метки.
            created_at: this.string(),
            updated_at: this.attr(''),
        }
    }

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

Category.entity = 'categories';
Category.primaryKey = 'id';

export default Category;
