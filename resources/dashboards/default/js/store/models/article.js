import Model from '@/store/model';

import Attachment from './attachment';
import Category from './category';
import Comment from './comment';
import Tag from './tag';
import User from './user';

class Article extends Model {
    static entity = 'articles';

    static state() {
        return {
            allowedFilters: {
                articles: {
                    'id': {
                        type: 'numeric'
                    },
                    'title': {
                        type: 'string'
                    },
                    'views': {
                        type: 'numeric'
                    },
                    'state': {
                        type: 'enum',
                        values: 'published,unpublished,draft'
                    },
                    'created_at': {
                        type: 'datetime'
                    }
                },
                categories: {
                    'categories.id': {
                        type: 'numeric'
                    },
                },
                comments: {
                    'comments.id': {
                        type: 'numeric'
                    },
                    'comments.count': {
                        type: 'counter'
                    },
                    'comments.content': {
                        type: 'string'
                    },
                    'comments.is_approved': {
                        type: 'boolean'
                    },
                    'comments.created_at': {
                        type: 'datetime'
                    }
                }
            }
        }
    }
}

export default Article;
