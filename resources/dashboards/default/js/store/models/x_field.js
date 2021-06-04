import Model from '@/store/model';

class XField extends Model {
    static entity = 'x_fields'

    static state() {
        return {
            extensibles: ['articles', 'categories', 'users',],
            field_types: ['string', 'integer', 'boolean', 'array', 'text', 'timestamp',]
        }
    }
}

export default XField
