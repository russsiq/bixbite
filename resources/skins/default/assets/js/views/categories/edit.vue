<template>
<div class="row">
    <div class="col col-sm-12">
        <form v-if="showedForm" action="" method="post" @submit.prevent="onSubmit" @keydown.ctrl.83.prevent="onSubmit">
            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Основные параметры</div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Отображать категорию в меню на панеле навигации</label>
                        </div>
                        <div class="col-sm-5">
                            <input type="checkbox" v-model="form.show_in_menu" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Заголовок <sup class="text-danger">*</sup></label>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" v-model="form.title" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class=" control-label">Ярлык для веб-страницы</label>
                            <small class="form-text text-muted">Оставьте пустым для автоматического создания.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" v-model="form.slug" class="form-control" />
                        </div>
                    </div>

                    <div v-if="!form.articles_count" class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Альтернативный URL</label>
                            <small class="form-text text-warning">В категорию, для которой прописан альт. URL, добавлять записи нельзя!</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" v-model="form.alt_url" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Параметры для главной страницы</div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Прикрепленное изображение</label>
                            <small class="form-text text-muted">Вы можете прикрепить изображение непосредственно к категории.</small>
                        </div>
                        <div class="col-sm-5">
                            <image-uploader v-model.number="form.image_id"></image-uploader>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Информация</label>
                            <small class="form-text text-muted">Информационный блок, отображаемый на странице категории сайта.</small>
                        </div>
                        <div class="col-sm-5">
                            <textarea v-model="form.info" rows="4" max="500" class="form-control" @keydown.enter.prevent></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Описание</label>
                            <small class="form-text text-muted">Мета тег description. Формируется только для главной страницы категории.</small>
                        </div>
                        <div class="col-sm-5">
                            <textarea v-model="form.description" rows="1" class="form-control" @keydown.enter.prevent></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Ключевые слова</label>
                            <small class="form-text text-muted">Мета тег keywords. Формируется только для главной страницы категории. Заполняется через запятую.</small>
                        </div>
                        <div class="col-sm-5">
                            <textarea v-model="form.keywords" rows="1" class="form-control" @keydown.enter.prevent></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Параметры отображения</div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Количество записей на странице</label>
                            <small class="form-text text-muted">Если оставить поле пустым, то число будет взято из общих настроек сайта.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model="form.paginate" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Сортировка записей на странице</label>
                        </div>
                        <div class="col-sm-5">
                            <select v-model="form.order_by" class="form-control">
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

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Порядок сортировки</label>
                        </div>
                        <div class="col-sm-5">
                            <select v-model="form.direction" class="form-control">
                                <option value="desc">По убыванию</option>
                                <option value="asc">По возрастанию</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="form.id" class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Активный шаблон</label>
                            <small class="form-text text-muted">Индивидуальные шаблоны для каждой категории, а также записей и комментариев к этим записям.<br>Должны быть расположены в папке <code>resources/themes/{theme}/views/custom_views/{category_slug}</code>.</small>
                        </div>
                        <div class="col-sm-5">
                            <select v-model="form.template" class="form-control">
                                <option :value="null">По умолчанию</option>
                                <option v-for="(template, key) in template_list" :value="key">{{ template }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="x_fields.length" class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Дополнительные поля</div>
                <div class="card-body">
                    <div v-for="field in x_fields" class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">{{ field.title }}</label>
                            <small class="form-text text-muted">{{ field.descr }}</small>
                        </div>
                        <div class="col-sm-5">
                            <template v-if="'string' === field.type">
                                <input type="text" v-model="form[field.name]" class="form-control" />
                            </template>
                            <template v-else-if="'integer' === field.type">
                                <input type="number" v-model="form[field.name]" class="form-control" />
                            </template>
                            <template v-else-if="'boolean' === field.type">
                                <input type="checkbox" v-model="form[field.name]" />
                            </template>
                            <template v-else-if="'array' === field.type">
                                <select v-model="form[field.name]" class="form-control">
                                    <option v-for="(param, index) in field.params" :value="param.key">{{ param.value }}</option>
                                </select>
                            </template>
                            <template v-else-if="'text' === field.type">
                                <textarea v-model="form[field.name]" rows="4" class="form-control"></textarea>
                            </template>
                            <template v-else-if="'timestamp' === field.type">
                                <input-datetime-local v-model="form[field.name]" class="form-control"></input-datetime-local>
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
                                <router-link :to="{name: 'categories'}" class="btn btn-outline-dark btn-bg-white ml-auto" exact>
                                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                                    <span class="d-none d-lg-inline">Отменить</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script type="text/ecmascript-6">
import {
    mapGetters,
    mapActions
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
            form: new this.$props.model,
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

        showedForm() {
            return Object.keys(this.form).length > 0;
        },

        isEditMode() {
            return this.$props.id > 0;
        },
    },

    async mounted() {
        if (this.isEditMode) {
            this.form = await this.$props.model.$get({
                params: {
                    id: this.$props.id
                }
            });
        }
    },

    methods: {
        async onSubmit(event) {
            const item = this.isEditMode ?
                await this.$props.model.$update({
                    params: {
                        id: this.form.id
                    },

                    data: {
                        ...this.form
                    }
                }) :
                await this.$props.model.$create({
                    data: {
                        ...this.form
                    }
                });

            this.form = Object.assign({}, this.form, {
                ...item
            });
        },
    },
}
</script>
