import Model from '@/store/model';

class Taggable extends Model {
    static fields() {
        return {
            id: this.attr(null),
            tag_id: this.number().nullable(),
            taggable_id: this.number().nullable(),
            taggable_type: this.string(),
        }
    }
}

Taggable.entity = 'taggables'

export default Taggable
