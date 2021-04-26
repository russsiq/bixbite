import Model from '@/store/model';

import User from './user';

class File extends Model {
    static state() {
        return {
            allowedFilters: {
                files: {
                    'id': {
                        type: 'numeric'
                    },
                    'title': {
                        type: 'string'
                    },
                    'name': {
                        type: 'string'
                    },
                    'type': {
                        type: 'enum',
                        values: 'archive,audio,document,image,video,other,forbidden'
                    },
                    'category': {
                        type: 'enum',
                        values: 'default'
                    },
                    'disk': {
                        type: 'enum',
                        values: 'public'
                        // values: 'local,public,s3'
                    },
                },
            }
        }
    }

    static fields() {
        return {
            id: this.attr(null),

            // Relation and other indexed keys.
            user_id: this.number().nullable(),
            parent_id: this.number().nullable(),
            attachment_id: this.number().nullable(),
            attachment_type: this.string().nullable(),

            // Main content.
            title: this.string().nullable(),
            description: this.string().nullable(),
            disk: this.attr('public'),
            category: this.string('default'),
            extension: this.string(),

            // Aditional.
            is_shared: this.boolean(false),

            // Счетчики.
            downloads: this.number(0),

            // Связи с другими сущностями.
            attachment: this.morphTo('attachment_id', 'attachment_type'),
            user: this.belongsTo(User, 'user_id'),

            // // Appending when download.
            // $table->string('type'); // ['archive', 'audio', 'doc', 'image', 'video', 'other']
            // $table->string('name');
            // $table->string('extension', 10);
            // $table->string('mime_type');
            // $table->integer('filesize'); // filesize();
            // $table->string('checksum', 32); // md5_file()
            // $table->text('properties')->nullable(); // json field type. Dimention, duration, etc.

            // Appended attribute.
            url: this.string('').nullable(),

            // Timestamps.
            created_at: this.string(),
            updated_at: this.attr(''),
        }
    }
}

File.entity = 'files';

export default File;
