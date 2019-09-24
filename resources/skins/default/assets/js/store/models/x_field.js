import {
    Model
} from '@vuex-orm/core'

class XField extends Model {
    static state() {
        return {
            extensibles: ['articles', 'categories', 'users',],
            field_types: ['string', 'integer', 'boolean', 'array', 'text', 'timestamp',]
        }
    }

    static fields() {
        return {
            id: this.increment(),
            extensible: this.string(),
            name: this.string(),
            type: this.string(),
            params: this.attr([]).nullable(),
            title: this.string().nullable(),
            descr: this.string().nullable(),
            html_flags: this.string().nullable(),

            // Timestamps.
            created_at: this.string(),
            updated_at: this.string().nullable(),
        }
    }
}

XField.entity = 'x_fields'

export default XField
