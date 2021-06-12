<template>
<form v-if="showedForm" action="" method="post" @submit.prevent="save" @keydown.ctrl.83.prevent="save" @input="runTimer">
    <div class="">
        <ul class="nav nav-tabs">
            <li class="nav-item active">
                <a href="#pane-main" data-toggle="tab" class="nav-link active">Основное содержимое</a>
            </li>
            <li class="nav-item">
                <a href="#pane-advanced" data-toggle="tab" class="nav-link">Дополнительно</a>
            </li>
        </ul>

        <br>

        <div class="tab-content">
            <div id="pane-main" class="tab-pane active">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-3 mb-2 order-first">
                        <image-uploader
                            :attachable="morphable"
                            :value="article.image_id"
                            @update:image_id="sync('image_id', $event)" />
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-5 mb-2 order-last">
                        <div class="mb-3 has-float-label">
                            <label class="control-label">Заголовок</label>
                            <div class="input-group">
                                <input type="text" v-model="article.title" maxlength="255" class="form-control" placeholder="Заголовок записи ..." autocomplete="off" required />
                                <a v-if="article.is_published" :href="article.url" target="_blank" class="btn btn-outline-primary"><i class="fa fa-external-link"></i></a>
                            </div>
                        </div>

                        <div class="mb-3 has-float-label">
                            <label class="control-label">Предисловие</label>
                            <textarea v-model="article.teaser" rows="4" maxlength="255" class="form-control noresize" placeholder="Заинтересуйте свою аудиторию ..." @keydown.13.prevent></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-4 mb-2 order-lg-last">
                        <div class="card card-default card-table">
                            <div class="card-header">Сводная информация</div>
                            <div class="card-body table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Автор</td>
                                            <td>{{ article.user && article.user.name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Состояние</td>
                                            <td><span :class="classState(article.state)">{{ titleState(article.state) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td>Дата создания</td>
                                            <td>{{ article.created_at | dateToString }}</td>
                                        </tr>
                                        <tr v-if="article.updated_at">
                                            <td>Дата обновления</td>
                                            <td>{{ article.updated_at | dateToString }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pane-advanced" class="tab-pane">
                <div class="row">
                    <div class="col-12 col-lg-8 mb-2">
                        <div v-if="setting.manual_meta" class="card card-default">
                            <div class="card-header">Мета данные</div>
                            <div class="card-body">
                                <div class="mb-3 has-float-label">
                                    <label class="control-label">Описание</label>
                                    <textarea v-model="article.meta_description" rows="2" maxlength="255" class="form-control"></textarea>
                                </div>
                                <div class="mb-3 has-float-label">
                                    <label class="control-label">Ключевые слова</label>
                                    <input type="text" v-model="article.meta_keywords" maxlength="255" class="form-control" autocomplete="off" />
                                </div>
                                <div class="mb-3 has-float-label">
                                    <label class="control-label">Инструкции для поисковых роботов</label>
                                    <select class="form-select" v-model="article.meta_robots">
                                        <option value="all">По умолчанию</option>
                                        <option value="noindex">noindex</option>
                                        <option value="nofollow">nofollow</option>
                                        <option value="none">none</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 mb-2">
                        <div class="card card-default">
                            <div class="card-header">Категории</div>
                            <div class="card-body p-0">
                                <multi-category-selector
                                    :categoryable="morphable"
                                    v-model="article.categories" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 mb-2">
            <div class="mb-3">
                <quill-editor :attachable="morphable" :value="article.content" @input="update('content', $event)" @json="updateAttributesFromJson" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-8 mb-2">
            <div id="accordion">
                <div v-if="x_fields.length" class="card card-default">
                    <div class="card-header"><i class="fa fa-puzzle-piece"></i> Дополнительные поля</div>
                    <div class="card-body">
                        <div v-for="field in x_fields" :key="field.id" class="mb-3 row">
                            <div class="col-sm-5">
                                <label class="control-label">{{ field.title }}</label>
                                <small class="form-text d-block text-muted">{{ field.description }}</small>
                            </div>
                            <div class="col-sm-7">
                                <template v-if="'string' === field.type">
                                    <input type="text" v-model="article[field.name]" class="form-control" v-bind="field.raw_html_flags" />
                                </template>
                                <template v-else-if="'integer' === field.type">
                                    <input type="number" v-model="article[field.name]" class="form-control" v-bind="field.raw_html_flags" />
                                </template>
                                <template v-else-if="'boolean' === field.type">
                                    <input type="checkbox" v-model="article[field.name]" v-bind="field.raw_html_flags" />
                                </template>
                                <template v-else-if="'timestamp' === field.type">
                                    <input-datetime-local v-model="article[field.name]" class="form-control" v-bind="field.raw_html_flags" />
                                </template>
                                <template v-else-if="'text' === field.type">
                                    <textarea v-model="article[field.name]" rows="4" class="form-control" v-bind="field.raw_html_flags"></textarea>
                                </template>
                                <template v-else-if="'array' === field.type">
                                    <select class="form-select" v-model="article[field.name]" v-bind="field.raw_html_flags">
                                        <option v-for="(param, index) in field.params" :key="index" :value="param.key">{{ param.value }}</option>
                                    </select>
                                </template>

                                <div v-else class="alert alert-danger">Неизвестный тип поля.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <a href="#card_tags" data-toggle="collapse" class="d-block"><i class="fa fa-tags text-muted"></i> Список тегов</a>
                    </div>
                    <div id="card_tags">
                        <div class="card-body">
                            <tags-items :taggable="morphable" :value.sync="article.tags"></tags-items>
                        </div>
                    </div>
                </div>

                <div class="card card-table">
                    <div class="card-header">
                        <a href="#card_files" data-toggle="collapse" class="d-block"><i class="fa fa-files-o text-muted"></i> Прикрепленные файлы</a>
                    </div>
                    <div id="card_files" class="card-body table-responsive">
                        <table v-if="article.attachments.length" class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Заголовок</th>
                                    <th>Расширение</th>
                                    <!-- <th>Категория</th> -->
                                    <th class="text-right d-print-none">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="attachment in article.attachments" :key="attachment.id">
                                    <td>{{ attachment.id }}</td>
                                    <td>{{ attachment.title }}</td>
                                    <td>{{ attachment.extension }}</td>
                                    <!-- <td>{{ attachment.category }}</td> -->
                                    <td class="text-right d-print-none">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-link" @click="deleteAttachment(attachment)">
                                                <i class="fa fa-trash-o text-danger"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p v-else class="alert alert-info text-center">К этой записи нет прикрепленных файлов.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
            <div class="card card-default">
                <div class="card-header">Параметры публикации</div>
                <div class="card-body">
                    <label class="control-label"><input type="checkbox" v-model="article.on_mainpage" /> Отобразить на главной</label>
                    <label class="control-label"><input type="checkbox" v-model="article.is_pinned" /> Прикрепить на главной</label>
                    <label class="control-label"><input type="checkbox" v-model="article.is_catpinned" /> Прикрепить в категории</label>
                    <label class="control-label"><input type="checkbox" v-model="article.is_favorite" /> Добавить в избранное</label>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">Управление временем публикации</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="control-label"><input type="radio" v-model="date_at" :value="null" /> Естесственное формирование даты</label>
                        <label class="control-label"><input type="radio" v-model="date_at" value="current" /> Установить текущую дату</label>
                        <label class="control-label"><input type="radio" v-model="date_at" value="custom" /> Установить дату вручную</label>
                    </div>

                    <div v-if="'custom' === date_at" class="mb-3">
                        <input-datetime-local v-model="article.created_at" class="form-control"></input-datetime-local>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">Комментирование</div>
                <div class="card-body">
                    <select class="form-select" v-model.number="article.allow_com">
                        <option value="2">По умолчанию</option>
                        <option value="1">Разрешить</option>
                        <option value="0">Запретить</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <pre v-if="isDebug" class="debug_box">{{ article.tags }}</pre>
</form>
</template>

<script type="text/ecmascript-6">
import {
    mapGetters
} from 'vuex';

import Quill from 'quill';

import Attachment from '@/store/models/attachment';

import QuillEditor from '@/components/quill-editor.vue';
import ImageUploader from '@/components/image-uploader.vue';
import InputDatetimeLocal from '@/components/input-datetime-local.vue';

// import CategorySelector from './partials/category-selector.vue';
import MultiCategorySelector from './partials/multi-category-selector.vue';
import TagsItems from './partials/tags-items';

export default {
    name: 'articles-edit',

    components: {
        'image-uploader': ImageUploader,
        'input-datetime-local': InputDatetimeLocal,
        // 'category-selector': CategorySelector,
        'multi-category-selector': MultiCategorySelector,
        'tags-items': TagsItems,
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
            article: {},

            date_at: null,

            saveTimer: null,
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
        }),

        isDebug() {
            return process.env.NODE_ENV !== 'production';
        },

        x_fields() {
            return this.meta.x_fields || [];
        },

        setting() {
            // Если настройки не заданы, то возвращаем значения по умолчанию.
            return this.meta.setting && this.meta.setting.articles || {
                'save_interval': 120,
            };
        },

        saveInterval() {
            const interval = Math.max(this.setting.save_interval, 120);

            return interval * 1000;
        },

        /**
         * Разрешено ли отобразить форму создания/редактирования.
         * @return {Boolean}
         */
        showedForm() {
            return this.article.id && this.article.id > 0;
        },

        classState() {
            return (state) => {
                const states = [
                    'text-danger', 'text-success', 'text-warning',
                ];

                return states[state] || states[0];
            }
        },

        titleState() {
            return (state) => {
                const states = [
                    'черновик', 'опубликована', 'на модерации',
                ];

                return states[state] || 'неизвестно';
            }
        },

        morphable() {
            return {
                id: this.$props.id,
                type: this.$props.model.entity,
            }
        },
    },

    watch: {
        'article.title'(val, oldVal) {
            document.title = val;
        },
    },

    created() {
        this.$props.model.$get(this.$props.id)
            .then(this.fillForm);
    },

    beforeDestroy() {
        clearTimeout(this.saveTimer);
    },

    methods: {
        fillForm(article) {
            clearTimeout(this.saveTimer);

            this.article = Object.assign({}, this.article, article);
        },

        updateAttributesFromJson(data) {
            for (const attribute in data) {
                if (this.article.hasOwnProperty(attribute)) {
                    this.article[attribute] = data[attribute];
                }
            }

            this.save();
        },

        sync(attribute, value) {
            this.update(attribute, value)
                .save();
        },

        update(attribute, value) {
            this.article[attribute] = value;

            return this;
        },

        /**
         * Отправить данные по текущей Записи на сервер.
         */
        save() {
            // Очищаем предыдущий таймер,
            // чтобы не было зацикливаний.
            clearTimeout(this.saveTimer);

            this.checkAndSetDate();

            this.$props.model.$update(this.$props.id, {
                    ...this.article,
                })
                .then(this.fillForm);
        },

        deleteAttachment(attachment) {
            if (this.article.image_id === attachment.id) {
                return alert(`Это основное изображение записи.`)
            }

            if (! confirm(`Хотите удалить это изображение [${attachment.id}] с сервера?`)) {
                return false;
            }

            const quillComponent = this.$children.find(
                component => component.editor && component.editor instanceof Quill
            );

            const html = quillComponent.editor.root;
            const [...images] = html.querySelectorAll('figure');
            const selectedImage = images.find(image => parseInt(image.dataset.id, 10) === attachment.id);

            selectedImage && selectedImage.remove();

            Attachment.$delete(attachment.id)
                .then((response) => {
                    this.save();
                });
        },
    },

    runTimer() {
        // Если таймер не запущен, то запускаем его.
        if (! this.saveTimer) {
            this.saveTimer = setTimeout(this.save, this.saveInterval);
        }
    },

    checkAndSetDate() {
        switch (this.date_at) {
            // Если необходимо задать текущую Дату.
            case 'current':
                this.article.created_at = new Date;
                this.article.updated_at = null;
                break;

            // Если необходимо взять Дату из поля ввода.
            case 'custom':
                this.article.created_at = this.article.created_at;
                this.article.updated_at = null;
                break;

            // По умолчанию оставляем Дату создания и обновляем Дату обновления.
            default:
                this.article.created_at = this.article.created_at || new Date;
                this.article.updated_at = new Date;
                break;
        }

        this.date_at = null;
    },
}
</script>

<style lang="scss" scoped>
.debug_box {
    background-color: #23241f;
    color: #f8f8f2;
    overflow: visible;
    white-space: pre-wrap;
    margin-top: 5px;
    padding: 5px 10px;
    border-radius: 3px;
}
</style>
