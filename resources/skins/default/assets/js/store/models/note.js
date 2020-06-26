import Model from '@/store/model';

import User from './user';
import File from './file';

class Note extends Model {
    static fields() {
        return {
            id: this.attr(null),
            user_id: this.number().nullable(),
            image_id: this.number().nullable(),

            title: this.string(),
            slug: this.string(),
            description: this.string(),
            is_completed: this.boolean(false),

            // Связи с другими сущностями.
            user: this.belongsTo(User, 'user_id'),
            files: this.morphMany(File, 'attachment_id', 'attachment_type'),
            image: this.hasOne(File, 'id', 'image_id'),

            // Временные метки.
            created_at: this.string(),
            updated_at: this.string().nullable(),
        }
    }

    /**
     * Получить CSS класс для кнопки,
     * переключающей атрибут `is_completed`.
     * @NB: в данный момент неизвестно насколько данный подход оправдан.
     * @return {Object}
     */
    get toggleButtonClass() {
        return {
            'fa fa-check text-success': this.is_completed,
            'fa fa-line-chart text-warning': !this.is_completed,
        }
    }
}

Note.entity = 'notes';

export default Note;
