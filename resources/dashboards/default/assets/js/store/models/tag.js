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
}

Tag.entity = 'tags'
Tag.primaryKey = 'id';

export default Tag;
