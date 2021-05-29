<template>
<form v-if="showedForm" action="" method="post" @submit.prevent="save" @keydown.ctrl.83.prevent="save">
    <div class="card card-default">
        <div class="card-header"><i class="fa fa-th-list"></i> Основные параметры</div>
        <div class="card-body">
            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Отображать категорию в меню на панеле навигации</label>
                </div>
                <div class="col-sm-5">
                    <input type="checkbox" v-model="category.show_in_menu" />
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Заголовок <sup class="text-danger">*</sup></label>
                </div>
                <div class="col-sm-5">
                    <input type="text" v-model="category.title" class="form-control" required />
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class=" control-label">Дружественный фрагмент URL-адреса веб-страницы</label>
                    <small class="form-text d-block text-muted">Оставьте пустым для автоматического создания.</small>
                </div>
                <div class="col-sm-5">
                    <input type="text" v-model="category.slug" class="form-control" />
                </div>
            </div>

            <div v-if="! category.articles_count" class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Ссылка на внешний ресурс</label>
                    <small class="form-text d-block text-warning">В категорию, для которой прописана данная ссылка, добавлять записи нельзя!</small>
                </div>
                <div class="col-sm-5">
                    <input type="text" v-model="category.alt_url" class="form-control" />
                </div>
            </div>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header"><i class="fa fa-th-list"></i> Параметры для главной страницы</div>
        <div class="card-body">
            <div v-if="category.id" class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Прикрепленное изображение</label>
                    <small class="form-text d-block text-muted">Вы можете прикрепить изображение непосредственно к категории.</small>
                </div>
                <div class="col-sm-5">
                    <image-uploader
                        :attachable="attachable"
                        :value.number="category.image_id"
                        @update:image_id="sync('image_id', $event)" />
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Информация</label>
                    <small class="form-text d-block text-muted">Информационный блок, отображаемый на странице категории сайта.</small>
                </div>
                <div class="col-sm-5">
                    <textarea v-model="category.info" rows="4" max="500" class="form-control" @keydown.enter.prevent></textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Описание</label>
                    <small class="form-text d-block text-muted">Мета тег description. Формируется только для главной страницы категории.</small>
                </div>
                <div class="col-sm-5">
                    <textarea v-model="category.description" rows="1" class="form-control" @keydown.enter.prevent></textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Ключевые слова</label>
                    <small class="form-text d-block text-muted">Мета тег keywords. Формируется только для главной страницы категории. Заполняется через запятую.</small>
                </div>
                <div class="col-sm-5">
                    <textarea v-model="category.keywords" rows="1" class="form-control" @keydown.enter.prevent></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header"><i class="fa fa-th-list"></i> Параметры отображения</div>
        <div class="card-body">
            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Количество записей на странице</label>
                    <small class="form-text d-block text-muted">Если оставить поле пустым, то число будет взято из общих настроек сайта.</small>
                </div>
                <div class="col-sm-5">
                    <input type="number" v-model.number="category.paginate" class="form-control" />
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Сортировка записей на странице</label>
                </div>
                <div class="col-sm-5">
                    <select class="form-select" v-model="category.order_by">
                        <option value="id">По умолчанию</option>
                        <option value="title">Заголовок</option>
                        <!-- <option value="votes">Количество голосов</option> -->
                        <!-- <option value="rating">Рейтинг пользователей</option> -->
                        <option value="views">Количество просмотров</option>
                        <option value="comments_count">Количество комментариев</option>
                        <option value="created_at">Дата создания</option>
                        <option value="updated_at">Дата обновления</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Порядок сортировки</label>
                </div>
                <div class="col-sm-5">
                    <select class="form-select" v-model="category.direction">
                        <option value="desc">По убыванию</option>
                        <option value="asc">По возрастанию</option>
                    </select>
                </div>
            </div>

            <div v-if="category.id" class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Активный шаблон</label>
                    <small class="form-text d-block text-muted">Индивидуальные шаблоны для каждой категории, а также записей и комментариев к этим записям.<br>Должны быть расположены в папке <code>resources/themes/{theme}/views/custom_views/{category_slug}</code>.</small>
                </div>
                <div class="col-sm-5">
                    <select class="form-select" v-model="category.template">
                        <option :value="null">По умолчанию</option>
                        <option v-for="(template, key) in template_list" :key="key" :value="key">{{ template }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div v-if="x_fields.length" class="card card-default">
        <div class="card-header"><i class="fa fa-th-list"></i> Дополнительные поля</div>
        <div class="card-body">
            <div v-for="field in x_fields" class="mb-3 row" :key="field.id">
                <div class="col-sm-7">
                    <label class="control-label">{{ field.title }}</label>
                    <small class="form-text d-block text-muted">{{ field.descr }}</small>
                </div>
                <div class="col-sm-5">
                    <template v-if="'string' === field.type">
                        <input type="text" v-model="category[field.name]" class="form-control" />
                    </template>
                    <template v-else-if="'integer' === field.type">
                        <input type="number" v-model="category[field.name]" class="form-control" />
                    </template>
                    <template v-else-if="'boolean' === field.type">
                        <input type="checkbox" v-model="category[field.name]" />
                    </template>
                    <template v-else-if="'array' === field.type">
                        <select class="form-select" v-model="category[field.name]">
                            <option v-for="(param, index) in field.params" :key="index" :value="param.key">{{ param.value }}</option>
                        </select>
                    </template>
                    <template v-else-if="'text' === field.type">
                        <textarea v-model="category[field.name]" rows="4" class="form-control"></textarea>
                    </template>
                    <template v-else-if="'timestamp' === field.type">
                        <input-datetime-local v-model="category[field.name]" class="form-control"></input-datetime-local>
                    </template>

                    <div v-else class="alert alert-danger">Неизвестный тип поля.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-footer">
            <div class="row">
                <div class="col-sm-5 offset-sm-7">
                    <div class="d-flex">
                        <button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
                            <span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
                            <span class="d-none d-md-inline">Сохранить</span>
                        </button>
                        <router-link :to="{name: 'categories'}" class="btn btn-outline-dark btn-bg-white ms-auto" exact>
                            <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                            <span class="d-none d-lg-inline">Отменить</span>
                        </router-link>
                    </div>
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

import ImageUploader from '@/components/image-uploader';
import InputDatetimeLocal from '@/components/input-datetime-local';

export default {
    name: 'categories-edit',

    components: {
        'image-uploader': ImageUploader,
        'input-datetime-local': InputDatetimeLocal,
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
            category: {
                show_in_menu: true,
                title: '',
                paginate: 8,
                order_by: 'id',
                template: null,
            },

            attachable: {
                id: this.$props.id,
                type: this.$props.model.entity
            },
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
        }),

        x_fields() {
            return this.meta.x_fields || [];
        },

        template_list() {
            return this.meta.template_list || [];
        },

        /**
         * Разрешено ли отобразить форму создания/редактирования.
         * @return {Boolean}
         */
        showedForm() {
            return Object.keys(this.category).length > 0;
        },

        /**
         * Если текущий режим – режим редактирования Заметки.
         * @return {Boolean}
         */
        isEditMode() {
            return this.$props.id > 0;
        },
    },

    mounted() {
        this.isEditMode && this.$props.model.$get(this.$props.id)
            .then(this.fillForm);
    },

    methods: {
        fillForm(category) {
            this.category = Object.assign({}, this.category, category);
        },

        sync(attribute, value) {
            this.update(attribute, value)
                .save();
        },

        update(attribute, value) {
            this.category[attribute] = value;

            return this;
        },

        save() {
            if (this.isEditMode) {
                return this.$props.model.$update(this.$props.id, {
                        ...this.category
                    })
                    .then(this.fillForm);
            } else {
                this.$props.model.$create({
                        ...this.category
                    })
                    .then(this.fillForm);
            }
        },
    },
}
</script>
