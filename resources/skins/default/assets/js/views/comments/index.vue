<template>
<filterable v-bind="filterable">
    <template #preaction>
        <div class="btn-group d-flex ml-auto">
            <router-link :to="{name: 'comments.settings'}" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></router-link>
        </div>
        <div class="btn-group ml-auto">
            <button class="btn btn-outline-dark" @click="toggleFilter"><i class="fa fa-filter"></i></button>
            <button class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
        </div>
    </template>

    <template #thead>
        <tr>
            <th>#</th>
            <th>Пользователь: комментарий</th>
            <th>Создан</th>
            <th class="text-right d-print-none">Действия</th>
            <th><input type="checkbox" v-model="selectedAll" title="Выбрать все" @click="selectAll" /></th>
        </tr>
    </template>

    <template #row="{row}">
        <tr :key="row.id">
            <td>{{ row.id }}</td>
            <td>
                <i>{{ row.user && row.user.name }}</i>: {{ row.content }}
            </td>
            <td style="white-space: nowrap;">{{ row.created_at | dateToString }}</td>
            <td class="text-right d-print-none">
                <div class="btn-group">
                    <button type="button" class="btn btn-link" @click="toggleStateComment(row)">
                        <i :class="classState(row.is_approved)"></i>
                    </button>

                    <a v-if="row.is_approved && row.url" :href="row.url" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>
                    <button v-else type="button" class="btn btn-link" disabled><i class="fa fa-external-link text-muted"></i></button>

                    <router-link :to="{name: 'comments.edit', params:{id: row.id}}" class="btn btn-link"><i class="fa fa-pencil"></i></router-link>

                    <button type="button" class="btn btn-link" @click="destroy(row)"><i class="fa fa-trash-o text-danger"></i></button>
                </div>
            </td>
            <td><input type="checkbox" :value="row.id" v-model="selected" /></td>
        </tr>
    </template>

    <template #tfoot>
        <tr>
            <td>#</td>
            <td>Пользователь: комментарий</td>
            <td>Создан</td>
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
                </optgroup>
                <!-- <optgroup label="Удалить">
                    <option value="delete">Удалить отмеченные</option>
                </optgroup> -->
            </select>
            <div class="input-group-append">
                <button type="submit" class="btn btn-outline-success" @click="applyMassAction">Применить</button>
            </div>
        </div>
    </template>
</filterable>
</template>

<script type="text/ecmascript-6">
import {
    put,
    destroy
} from '@/helpers/api';

import Filterable from '@/views/components/filterable';

export default {
    name: 'comments',

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
        classState() {
            return (is_approved) => is_approved ? 'fa fa-check text-success' : 'fa fa-times text-warning';
        },
    },

    mounted() {
        //
    },

    beforeDestroy() {
        this.$props.model.deleteAll();
    },

    methods: {
        toggleFilter() {
            this.filterable.active = !this.filterable.active;
        },

        toggleStateComment(row) {
            this.massUpdate([row.id], row.is_approved ? 'unpublished' : 'published');
        },

        selectAll() {
            this.selected = [];

            if (!this.selectedAll) {
                const items = this.$props.model.all();

                this.selected = items.map(item => item.id);
            }
        },

        applyMassAction() {
            if (!this.selected.length) {
                return Notification.warning({
                    message: 'Пожалуйста, выберите комментарии.'
                });
            }

            if (!this.massAction) {
                return Notification.warning({
                    message: 'Пожалуйста, выберите действие.'
                });
            }

            const action = this.massAction.toString();

            if (action.startsWith('delete')) {
                return this.massDelete(this.selected, action);
            }

            return this.massUpdate(this.selected, action);
        },

        async massUpdate(comments, mass_action) {
            // Не использовать это: this.$props.model.$update(...)
            // Потому что ArticleRequest удаляет отсутствующие атрибуты.

            const response = await put(`${Pageinfo.api_url}/comments`, {
                comments,
                mass_action,
            });

            // Before used insertOrUpdate, need to delete all morph relation.
            // Because duplicates are created.
            // response.data.data.forEach(item => {
            //     delete item.categories
            //     delete item.tags
            // })

            const data = await this.$props.model.insertOrUpdate({
                where: (record) => comments.includes(record.id),
                data: response.data.data,
            });

            // const ids = data.comments.map(comment => comment.id);
            //
            // ids.length && Notification.success({
            //     message: `Записи обновлены: [${ids.toString()}].`
            // });
        },

        massDelete(comments) {
            comments.map(id => this.destroy({
                id
            }));
        },

        /**
         * Delete the comment.
         */
        destroy(comment) {
            const result = confirm(`Вы точно хотите удалить этот комментарий: [${comment.id}] с прикрепленными файлами?`);

            result && this.$props.model.$delete({
                params: {
                    id: comment.id
                }
            });
        },
    },
}
</script>
