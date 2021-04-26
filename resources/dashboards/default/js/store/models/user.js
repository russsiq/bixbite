import Model from '@/store/model';

import Article from './article';
import File from './file';

class User extends Model {
    static fields() {
        return {
            id: this.attr(null),
            name: this.string(''),
            email: this.string(''),
            role: this.string(''),

            // Дополнительная информация.
            avatar: this.string('').nullable(),
            info: this.string('').nullable(),
            where_from: this.string('').nullable(),
            last_ip: this.string('').nullable(),

            // Скрываемые атрибуты.
            // password: this.string(''),
            // remember_token: this.string('').nullable(),
            // api_token: this.string('').nullable(),

            // Связи с другими сущностями.
            articles: this.hasMany(Article, 'user_id', 'id'),
            files: this.hasMany(File, 'user_id', 'id'),

            // Динамически добавляемые атрибуты.
            is_online: this.boolean(false),
            articles_count: this.number(0),
            comments_count: this.number(0),

            // Временные метки.
            created_at: this.string(), // registered_at
            updated_at: this.attr('').nullable(),
            logined_at: this.attr('').nullable(),
            email_verified_at: this.attr('').nullable(),
        }
    }
}

User.entity = 'users';

export default User;
