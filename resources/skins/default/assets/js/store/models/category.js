import {
    Model
} from '@vuex-orm/core';

import Article from './article';
import Categoryable from './categoryable';
import File from './file';

class Category extends Model {
    static fields() {
        return {
            id: this.increment(),
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
            files: this.morphMany(File, 'attachment_id', 'attachment_type'),
            image: this.hasOne(File, 'id', 'image_id'),

            // Счетчики для других сущностей.
            articles_count: this.number(0),
            files_count: this.number(0),

            // Appends attributes.
            url: this.attr(null),

            // Временные метки.
            created_at: this.string(),
            updated_at: this.attr(''),
        }
    }
}

Category.entity = 'categories';

export default Category;
