<!--
     - обернуть в тег `form`;
     - убрать обработку ошибок - они все во всплывающих подсказках;
     - убрать `name` у полей ввода - вроде как не нужны;

-->

<template>
<form v-if="showedForm" action="" method="post" @submit.prevent="update" @keydown.ctrl.83.prevent="update">
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-3 mb-2 order-first">
            <image-uploader v-model.number="form.image_id" @input="this.update"></image-uploader>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-5 mb-2 order-last">
            <div class="form-group has-float-label">
                <label class="control-label">Заголовок</label>
                <div class="input-group">
                    <input type="text" v-model="form.title" maxlength="255" class="form-control" placeholder="Заголовок записи ..." autocomplete="off" required />
                    <div v-if="isPublished" class="input-group-append">
                        <a :href="form.url" target="_blank" class="btn btn-outline-secondary"><i class="fa fa-external-link"></i></a>
                    </div>
                </div>

                <!-- <transition name="fade">
                    <span v-if="errors.title" class="invalid-feedback">{{ errors.title[0] }}</span>
                </transition> -->
            </div>
            <div class="form-group has-float-label">
                <label class="control-label">Предисловие</label>
                <textarea v-model="form.teaser" rows="4" maxlength="255" class="form-control noresize" placeholder="Заинтересуйте свою аудиторию ..." @keydown.13.prevent></textarea>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-4 mb-2 order-lg-last">
            <div class="form-group has-float-label">
                <label for="catmenu" class="control-label">Категории</label>
                <categories-items v-model="form.categories" :selected="form.categories"></categories-items>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 mb-2">
            <div class="form-group">

                <div class="card">
                    <div class="card-header">
                        <a href="#card_files" data-toggle="collapse" class="d-block"><i class="fa fa-files-o text-muted"></i> Прикрепленные файлы</a>
                    </div>
                    <div id="card_files" class="c-ollapse">
                        <ul v-if="article.files">
                            <li v-for="file in article.files" @click="fileDelete(file)">{{ file.id }}</li>
                        </ul>
                    </div>
                </div>

                <quill-editor :attachment="attachment" :value="form.content" @input="updateContent" @json="updateAttributesFromJson"></quill-editor>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-8 mb-2">
            <div id="accordion">
                <div v-if="setting.manual_meta" class="card">
                    <div class="card-header">
                        <a href="#card_meta" data-toggle="collapse" class="d-block"><i class="fa fa-header text-muted"></i> Мета данные</a>
                    </div>
                    <div id="card_meta" class="collapse">
                        <div class="card-body">
                            <div class="form-group has-float-label">
                                <label class="control-label">Описание</label>
                                <textarea v-model="form.description" rows="3" maxlength="255" class="form-control"></textarea>
                            </div>
                            <div class="form-group has-float-label">
                                <label class="control-label">Ключевые слова</label>
                                <input type="text" v-model="form.keywords" maxlength="255" class="form-control" autocomplete="off">
                            </div>
                            <div class="form-group has-float-label">
                                <label class="control-label">Инструкции для поисковых роботов</label>
                                <select v-model="form.robots" class="form-control">
                                    <option :value="null">По умолчанию</option>
                                    <option value="noindex">noindex</option>
                                    <option value="nofollow">nofollow</option>
                                    <option value="none">none</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <a href="#card_tags" data-toggle="collapse" class="d-block"><i class="fa fa-tags text-muted"></i> Список тегов</a>
                    </div>
                    <div id="card_tags" class="collapse">
                        <div class="card-body">
                            <div class="form-group has-float-label">
                                <label class="control-label">Теги</label>
                                <input type="text" v-model="form.tags" maxlength="255" class="form-control" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <pre>{{ form }}</pre>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
            <div class="card card-default card-table">
                <div class="card-header">Сводная информация</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td>Автор</td>
                                <td>{{ form.user && form.user.name }}</td>
                            </tr>
                            <tr>
                                <td>Состояние</td>
                                <td><span :class="classState(form.state)">{{ titleState(form.state) }}</span></td>
                            </tr>
                            <tr>
                                <td>Создание</td>
                                <td>{{ form.created_at }}</td>
                            </tr>
                            <tr>
                                <td>Обновление</td>
                                <td>{{ form.updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">Параметры публикации</div>
                <div class="card-body">
                    <label class="control-label"><input type="checkbox" v-model="form.on_mainpage" /> Отобразить на главной</label>
                    <label class="control-label"><input type="checkbox" v-model="form.is_pinned" /> Прикрепить на главной</label>
                    <label class="control-label"><input type="checkbox" v-model="form.is_catpinned" /> Прикрепить в категории</label>
                    <label class="control-label"><input type="checkbox" v-model="form.is_favorite" /> Добавить в избранное</label>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">Управление временем публикации</div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="control-label"><input type="radio" v-model="date_at" :value="null" /> Естесственное формирование даты</label>
                        <label class="control-label"><input type="radio" v-model="date_at" value="currdate" /> Установить текущую дату</label>
                        <label class="control-label"><input type="radio" v-model="date_at" value="customdate" /> Установить дату вручную</label>
                    </div>

                    <div v-if="'customdate' === date_at" class="form-group">
                        <input-datetime-local v-model="form.created_at" class="form-control"></input-datetime-local>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">Комментирование</div>
                <div class="card-body">
                    <select v-model.number="form.allow_com" class="form-control">
                        <option value="2">По умолчанию</option>
                        <option value="1">Разрешить</option>
                        <option value="0">Запретить</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
</template>

<script type="text/ecmascript-6">
import {
    mapGetters
} from 'vuex';
import Quill from 'quill';

import File from '@/store/models/file';

import QuillEditor from '@/components/quill-editor.vue';
import ImageUploader from '@/components/image-uploader.vue';
import InputDatetimeLocal from '@/components/input-datetime-local.vue';

import CategoriesItems from './partials/categories-items';

export default {
    name: 'articles-edit',

    components: {
        'image-uploader': ImageUploader,
        'input-datetime-local': InputDatetimeLocal,
        'categories-items': CategoriesItems,
        'quill-editor': QuillEditor,
    },

    props: {
        model: {
            type: Function,
            required: true
        },

        id: {
            type: Number,
            required: true
        },
    },

    data() {
        return {
            form: {},
            date_at: null,

            saveTimer: null,

            attachment: {
                id: this.$props.id,
                type: this.$props.model.entity
            },
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
        }),

        setting() {
            // Если настройки не заданы,
            // то возвращаем пустой массив.
            return this.meta.setting.articles || [];
        },

        saveInterval() {
            const interval = Math.max(this.setting.save_interval || 120, 120);

            return interval * 1000;
        },

        // article() {
        //     // Load article with all relations. withAllRecursive()
        //     return this.model.query().withAll().find(this.$props.id)
        //
        //     // Load nested relations with dot syntax
        //     // const user = User.query() .with('posts.comments|reviews').find(1)
        // },

        showedForm() {
            return Object.keys(this.form).length > 0;
        },

        isPublished() {
            return 'published' === this.form.state;
        },

        article() {
            return this.$props.model.query().withAllRecursive().find(this.$props.id);
        },

        classState() {
            return (state) => {
                const states = {
                    published: 'text-success',
                    unpublished: 'text-warning',
                };

                return states[state] || 'text-danger';
            }
        },

        titleState() {
            return (state) => {
                const states = {
                    published: 'опубликована',
                    unpublished: 'на модерации',
                };

                return states[state] || 'черновик';
            }
        },
    },

    watch: {
        'form.title'(val, oldVal) {
            document.title = val;
        }
    },

    /**
     * Prepare the component.
     */
    created() {
        this.$props.model.$get({
                params: {
                    id: this.$props.id
                }
            })
            .then(this.fillForm);
    },

    /**
     * Clean after the component is destroyed.
     */
    beforeDestroy() {
        // Teardown the watcher.
        clearTimeout(this.saveTimer);

        // Reset resource list.
        this.$props.model.deleteAll();
    },

    methods: {
        /**
         * Добавить поле для настройки в форму.
         * @param {Article} article
         */
        fillForm(article) {
            this.form = Object.assign({}, this.form, article);
            this.form.categories = this.form.categories.map(cat => cat.id);
            this.form.tags = this.form.tags.map(tag => tag.title).join(', ');

            this.saveTimer = setTimeout(this.update, this.saveInterval);
        },

        updateAttributesFromJson(data) {
            for (let attribute in data) {
                if (this.form.hasOwnProperty(attribute)) {
                    this.form[attribute] = data[attribute];
                }
            }

            this.update();
        },

        updateContent(content) {
            this.form.content = content;
        },

        updateImage(id) {
            this.form.image_id = id;
        },

        /**
         * Update the article.
         */
        update() {
            // Очищаем предыдущий таймер,
            // чтобы не было зацикливаний.
            clearTimeout(this.saveTimer);

            this.model.$update({
                    params: {
                        id: this.$props.id
                    },

                    data: {
                        ...this.form,
                        // Дополнительные поля.
                        // Переписать на сторону клиента.
                        date_at: this.date_at
                    },

                    // Не понятно: работает ли это?
                    // update: ['files']
                })
                .then(this.fillForm)
                .catch((error) => {})
                .then(() => {});
        },

        fileDelete(file) {
            if (this.form.image_id === file.id) return;

            const result = confirm(`Вы точно хотите удалить это изображение [${file.id}] с сервера?`);

            if (result) {
                const quillComponent = this.$children.find((component) => {
                    return component.editor && component.editor instanceof Quill;
                });

                const html = quillComponent.editor.root;
                const [...images] = html.querySelectorAll('figure');
                const selectedImage = images.find(image => +image.dataset.id === file.id);

                selectedImage && selectedImage.remove();

                File.$delete({
                    params: {
                        id: file.id
                    }
                });
            }
        },
    },
}
</script>

<!--style lang="scss" scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
/* .fade-leave-active до версии 2.1.8 */
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style-->
