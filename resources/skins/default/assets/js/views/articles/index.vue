<template>
<filterable v-bind="filterable" :value="collection" @apply:change="fetch">
    <template #preaction>
        <button type="button" class="btn btn-outline-dark" @click="create"><i class="fa fa-plus"></i> Создать</button>
        <div class="btn-group d-flex ml-auto">
            <router-link :to="{name: 'categories'}" class="btn btn-outline-dark" title="Категории"><i class="fa fa-folder-open-o"></i></router-link>
            <router-link :to="{name: 'x_fields'}" class="btn btn-outline-dark" title="Дополнительные поля"><span class="as-icon">χφ</span></router-link>
            <router-link :to="{name: 'articles.settings'}" class="btn btn-outline-dark" title="Настройки"><i class="fa fa-cogs"></i></router-link>
        </div>
        <div class="btn-group ml-auto">
            <button type="button" class="btn btn-outline-dark" @click="toggleFilter"><i class="fa fa-filter"></i></button>
            <button type="button" class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
        </div>
    </template>

    <template #thead>
        <tr>
            <th>#</th>
            <th>Заголовок</th>
            <th class="hidden-xs">Категория</th>
            <th class="hidden-xs">Автор</th>
            <th class="hidden-xs"><i class="fa fa-eye"></i></th>
            <th class="hidden-xs"><i class="fa fa-comments"></i></th>
            <th class="hidden-xs"></th>
            <th class="text-right d-print-none">Действия</th>
            <th><input type="checkbox" v-model="selectedAll" title="Выбрать все" @click="selectAll" /></th>
        </tr>
    </template>

    <template #row="{row}">
        <tr :key="row.id">
            <td>{{ row.id }}</td>
            <td>
                <router-link :to="editpage(row)" class="d-block">{{ row.title }}</router-link>
                [{{ row.created_at | dateToString }}] <span v-if="row.updated_at">[{{ row.updated_at | dateToString }}]</span>
            </td>
            <td class="hidden-xs">
                <span v-for="category in row.categories" class="cat-links mr-1">
                    <a :href="category.url" target="_blank">{{ category.title }}</a>
                </span>
            </td>
            <td class="hidden-xs">{{ row.user && row.user.name }}</td>
            <td class="hidden-xs">{{ row.views }}</td>
            <td class="hidden-xs">{{ row.comments_count }}</td>
            <td class="hidden-xs">
                <i v-if="row.files_count > 0" class="fa fa-paperclip" title="К записи прикреплены файлы."></i>
            </td>
            <td class="text-right d-print-none">
                <div class="btn-group">
                    <button type="button" class="btn btn-link" @click="toggleStateArticle(row)"><i :class="classState(row.state)" :title="__(row.state)"></i></button>
                    <button type="button" class="btn btn-link" @click="massUpdate([row.id], 'is_favorite')"><i :class="classIsFavorite(row.is_favorite)"></i></button>
                    <button type="button" class="btn btn-link" @click="massUpdate([row.id], 'is_catpinned')"><i :class="classIsCatpinned(row.is_catpinned)"></i></button>
                    <button type="button" class="btn btn-link" @click="massUpdate([row.id], 'on_mainpage')"><i :class="classOnMainpage(row.on_mainpage)"></i></button>

                    <a v-if="'published' === row.state" :href="row.url" target="_blank" class="btn btn-link">
                        <i class="fa fa-external-link"></i>
                    </a>
                    <button v-else type="button" class="btn btn-link" disabled><i class="fa fa-eye-slash text-muted"></i></button>

                    <router-link :to="editpage(row)" class="btn btn-link"><i class="fa fa-pencil"></i></router-link>

                    <button type="button" class="btn btn-link" @click="destroy(row)"><i class="fa fa-trash-o text-danger"></i></button>
                </div>
            </td>
            <td><input type="checkbox" :value="row.id" v-model="selected" /></td>
        </tr>
    </template>

    <template #tfoot>
        <tr>
            <td>#</td>
            <td>Заголовок</td>
            <td class="hidden-xs">Категория</td>
            <td class="hidden-xs">Автор</td>
            <td class="hidden-xs"><i class="fa fa-eye"></i></td>
            <td class="hidden-xs"><i class="fa fa-comments"></i></td>
            <td class="hidden-xs"></td>
            <td class="text-right d-print-none">Действия</td>
            <td><input type="checkbox" v-model="selectedAll" title="Выбрать все" @click="selectAll" /></td>
        </tr>
    </template>

    <template #action>
        <div class="input-group">
            <select v-model="massAction" class="form-control">
                <option value="" disabled selected>Выберите действие</option>
                <optgroup label="Статус">
                    <option value="published">Опубликовать</option>
                    <option value="unpublished">Отправить на модерацию</option>
                    <option value="draft">Сохранить как черновик</option>
                </optgroup>
                <optgroup label="Главная страница">
                    <option value="on_mainpage">Отобразить/скрыть на главной</option>
                </optgroup>
                <optgroup label="Комментарии">
                    <option value="allow_com">Разрешить/запретить комментарии</option>
                </optgroup>
                <optgroup label="Дата">
                    <option value="currdate">Установить текущую дату</option>
                </optgroup>
                <optgroup label="Удалить">
                    <option value="delete">Удалить отмеченные</option>
                </optgroup>
            </select>
            <div class="input-group-append">
                <button type="submit" class="btn btn-outline-success" @click="applyMassAction">Применить</button>
            </div>
        </div>
    </template>
</filterable>
</template>

<script type="text/ecmascript-6">
import Filterable from '@/views/components/filterable';

export default {
    name: 'articles',

    components: {
        'filterable': Filterable
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            selected: [],
            collection: [],
            selectedAll: false,
            massAction: '',
            filterable: {
                model: this.$props.model,
                active: false,
                massAction: true,
            }
        }
    },

    computed: {
        editpage() {
            return (article) => ({
                name: 'articles.edit',
                params: {
                    id: article.id
                }
            });
        },

        classState() {
            return (state) => {
                const states = {
                    published: 'fa fa-check text-success',
                    unpublished: 'fa fa-times text-warning',
                    draft: 'fa fa-ban text-danger',
                };

                return states[state] || 'fa fa-question text-danger';
            }
        },

        classIsFavorite() {
            return (is_favorite) => is_favorite ?
                'fa fa-star text-warning' :
                'fa fa-star-o text-muted';
        },

        classIsCatpinned() {
            return (is_catpinned) => is_catpinned ?
                'fa fa-thumb-tack fa-rotate-90 text-danger' :
                'fa fa-thumb-tack text-muted';
        },

        classOnMainpage() {
            return (on_mainpage) => on_mainpage ?
                'fa fa-home text-success' :
                'fa fa-home text-muted';
        },
    },

    methods: {
        toggleFilter() {
            this.filterable.active = !this.filterable.active;
        },

        toggleStateArticle(row) {
            const state = 'published' !== row.state ? 'published' : 'draft';

            row.categories.length && this.massUpdate([row.id], state);
        },

        selectAll() {
            this.selected = [];

            if (!this.selectedAll) {
                this.selected = this.collection.map(item => item.id);
            }
        },

        fetch(filter) {
            this.$props.model.$fetch(filter)
                .then(this.fillTable);
        },

        fillTable(collection) {
            this.collection = collection;
        },

        create() {
            const title = prompt('Укажите заголовок новой Записи:', 'Черновик');

            title && this.$props.model.$create({
                data: {
                    title
                }
            });
        },

        applyMassAction() {
            if (!this.selected.length) {
                return this.$notification.warning({
                    message: 'Пожалуйста, выберите Записи.'
                });
            }

            if (!this.massAction) {
                return this.$notification.warning({
                    message: 'Пожалуйста, выберите действие.'
                });
            }

            const action = this.massAction.toString();

            if (action.startsWith('delete')) {
                return this.massDelete(this.selected);
            }

            return this.massUpdate(this.selected, action);
        },

        massUpdate(articles, mass_action) {
            this.$props.model.$massUpdate(articles, mass_action)
                .then((collection) => {
                    this.collection = this.collection.map((article) => {
                        if (articles.includes(article.id)) {
                            const updated = collection.find(item => item.id === article.id);

                            return {
                                ...article,
                                ...updated
                            };
                        }

                        return article;
                    });
                });
        },

        massDelete(articles) {
            articles.map(id => this.destroy({
                id
            }));
        },

        destroy(article) {
            const result = confirm(`Хотите удалить эту Запись [${article.title}] с прикрепленными файлами?`);

            result && this.$props.model.$delete({
                    params: {
                        id: article.id
                    }
                })
                .then((response) => {
                    this.collection = this.collection.filter((item) => item.id !== article.id);
                });
        }
    },
}
</script>
