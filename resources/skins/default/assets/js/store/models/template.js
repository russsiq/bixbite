import Model from '@/store/model';

class Template extends Model {
    static fields() {
        return {
            // `vuex-orm` самостоятельно генерирует и увеличивает `id`.
            id: this.attr(null),
            // Путь от каталога с темой, включая имя файла.
            filename: this.string(),
            // Путь от корневого каталога, включая имя файла.
            path: this.string(),
            exists: this.boolean(false),
            content: this.string(),
            modified: this.string(),
            size: this.string(),
        }
    }
}

Template.entity = 'templates';

export default Template;
