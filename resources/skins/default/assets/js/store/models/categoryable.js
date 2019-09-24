import {
    Model
} from '@vuex-orm/core'

class Categoryable extends Model {
    static fields() {
        return {
            id: this.attr(null), // important: attr to null
            category_id: this.number().nullable(),
            categoryable_id: this.number().nullable(),
            categoryable_type: this.string(),
        }
    }
}

Categoryable.entity = 'categoryables'

export default Categoryable
