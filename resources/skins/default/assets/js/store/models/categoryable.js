import Model from '@/store/model';

class Categoryable extends Model {
    static fields() {
        return {
            id: this.attr(null),
            category_id: this.number().nullable(),
            categoryable_id: this.number().nullable(),
            categoryable_type: this.string(),
        }
    }
}

Categoryable.entity = 'categoryables'

export default Categoryable
