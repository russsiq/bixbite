import Model from '@/store/model';

class Setting extends Model {
    static state() {
        return {}
    }

    static fields() {
        return {
            id: this.attr(null),
            module_name: this.string(),
            name: this.string(),
            type: this.string('string'),
            value: this.string(),

            // Временные метки.
            created_at: this.string(),
            updated_at: this.string().nullable(),
        }
    }
}

Setting.entity = 'settings';

export default Setting
