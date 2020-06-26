import Model from '@/store/model';

import User from './user';

class Comment extends Model {
    static state() {
        return {
            allowedFilters: {
                comments: {
                    'id': {
                        type: 'numeric'
                    },
                    'user_id': {
                        type: 'numeric'
                    },
                    'content': {
                        type: 'string'
                    },
                    'commentable_type': {
                        type: 'string'
                    },
                    'commentable_id': {
                        type: 'numeric'
                    },
                    'is_approved': {
                        type: 'boolean'
                    },
                    'created_at': {
                        type: 'datetime'
                    }
                }
            }
        }
    }

    static fields() {
        return {
            id: this.attr(null),

            // Связи с другими сущностями,
            // а также индексные ключи.
            parent_id: this.number().nullable(),
            user_id: this.number().nullable(),
            user: this.belongsTo(User, 'user_id'),
            commentable_id: this.number().nullable(),
            commentable_type: this.string().nullable(),
            commentable: this.morphTo('commentable_id', 'commentable_type'),

            // Main content.
            content: this.string().nullable(),
            name: this.string().nullable(),
            email: this.string().nullable(),
            user_ip: this.attr(''),

            // Aditional.
            is_approved: this.boolean(false),

            // Appends attributes.
            url: this.string().nullable(),

            // Временные метки.
            created_at: this.string(),
            updated_at: this.attr(''),
        }
    }
}

Comment.entity = 'comments';

export default Comment;
