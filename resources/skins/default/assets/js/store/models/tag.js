import {
    Model
} from '@vuex-orm/core'

import Article from './article'
import Taggable from './taggable'

class Tag extends Model {
    static fields() {
        return {
            id: this.increment(),
            title: this.string(''),
            
            articles: this.morphedByMany(Article, Taggable, 'tag_id', 'taggable_id', 'taggable_type'),
        }
    }
}

Tag.entity = 'tags'

export default Tag
