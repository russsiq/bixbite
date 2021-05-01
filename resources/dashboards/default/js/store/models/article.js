import Model from '@/store/model';

import Attachment from './attachment';
import Category from './category';
import Comment from './comment';
import Tag from './tag';
import User from './user';

class Article extends Model {
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

    static fields() {
        return {
            id: this.attr(null),
            categories: this.morphToMany(Category, Categoryable, 'category_id', 'categoryable_id', 'categoryable_type'),
            comments: this.morphMany(Comment, 'commentable_id', 'commentable_type'),
            attachments: this.morphMany(Attachment, 'attachable_id', 'attachable_type'),
            tags: this.morphToMany(Tag, Taggable, 'tag_id', 'taggable_id', 'taggable_type'),
            user_id: this.number(1),
            image_id: this.number().nullable(),

            // Main content.
            state: this.string('unpublished'),
            title: this.string().nullable(),
            slug: this.string().nullable(),
            teaser: this.string().nullable(),
            content: this.string().nullable(),

            // Meta.
            description: this.string().nullable(),
            keywords: this.string().nullable(),
            robots: this.string().nullable(),

            on_mainpage: this.boolean(true),
            is_favorite: this.boolean(false),
            is_pinned: this.boolean(false),
            is_catpinned: this.boolean(false),
            allow_com: this.number(2),

            // Счетчики.
            shares: this.number(0),
            views: this.number(0),
            votes: this.number().nullable(),
            rating: this.number().nullable(),

            // Count relationship.
            comments_count: this.number(0),
            attachments_count: this.number(0),

            // Appends attributes.
            url: this.attr('').nullable(),

            // Связи с другими сущностями.
            //img: this.string().nullable(),
            user: this.belongsTo(User, 'user_id'),
            image: this.hasOne(Attachment, 'image_id', 'attachable_id'),

            // Временные метки.
            created_at: this.string(),
            updated_at: this.string().nullable(),
        }
    }
}

Article.entity = 'articles';
Article.primaryKey = 'id';

export default Article;
