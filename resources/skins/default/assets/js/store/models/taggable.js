import {
    Model
} from '@vuex-orm/core'

class Taggable extends Model {
    static fields() {
        return {
            id: this.attr(null), // important: attr to null
            tag_id: this.number().nullable(),
            taggable_id: this.number().nullable(),
            taggable_type: this.string(),
        }
    }
}

Taggable.entity = 'taggables'

export default Taggable
