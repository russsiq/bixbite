import Model from '@/store/model';

import Article from './article'

class Tag extends Model {
    static fields() {
        return {
            id: this.attr(null),
            title: this.string(''),

            articles: this.morphedByMany(Article, Taggable, 'tag_id', 'taggable_id', 'taggable_type'),
        }
    }

    static $attach({taggable, tag}, data, config = {}) {
        return this.api()
            .post(`taggable/${taggable.type}/${taggable.id}/tags/${tag.id}`, data, config);
    }

    static $detach({taggable, tag}, config = {}) {
        return this.api()
            .delete(`taggable/${taggable.type}/${taggable.id}/tags/${tag.id}`, config);
    }
}

Tag.entity = 'tags';
Tag.primaryKey = 'id';

export default Tag;
