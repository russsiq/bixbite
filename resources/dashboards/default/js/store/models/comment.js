import Model from '@/store/model';

class Comment extends Model {
    static entity = 'comments';

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
}

export default Comment;
