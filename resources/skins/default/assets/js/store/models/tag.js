import Model from '@/store/model';

import Article from './article'
import Taggable from './taggable'

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

export default Tag
