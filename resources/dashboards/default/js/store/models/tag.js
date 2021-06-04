import Model from '@/store/model';

class Tag extends Model {
    static entity = 'tags';

    static $attach({taggable, tag}, data, config = {}) {
        return this.api()
            .post(`taggable/${taggable.type}/${taggable.id}/tags/${tag.id}`, data, config);
    }

    static $detach({taggable, tag}, config = {}) {
        return this.api()
            .delete(`taggable/${taggable.type}/${taggable.id}/tags/${tag.id}`, config);
    }
}

export default Tag;
