import Model from '@/store/model';

class Attachment extends Model {
    static entity = 'attachments';

    static state() {
        return {
            allowedFilters: {
                attachments: {
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
}

export default Attachment;
