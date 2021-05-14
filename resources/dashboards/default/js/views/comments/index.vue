<template>
<filterable v-bind="filterable" :value="collection" @apply:change="fetch">
    <template #preaction>
        <div class="btn-group d-flex ms-auto">
            <router-link :to="{name: 'comments.settings'}" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></router-link>
        </div>
        <div class="btn-group ms-auto">
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
                <b>{{ row.user && row.user.name }}</b>: {{ row.content }}
            </td>
            <td style="white-space: nowrap;">{{ row.created_at | dateToString }}</td>
            <td class="text-right d-print-none">
                <div class="btn-group">
                    <button type="button" class="btn btn-link" @click="toggleStateComment(row)">
                        <i :class="classState(row.is_approved)"></i>
                    </button>

                    <a v-if="row.is_approved && row.url" :href="row.url" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>
                    <button v-else type="button" class="btn btn-link" disabled><i class="fa fa-external-link text-muted"></i></button>

                    <!-- <router-link :to="{name: 'comments.edit', params:{id: row.id}}" class="btn btn-link"><i class="fa fa-pencil"></i></router-link> -->

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
            <select class="form-select" v-model="massAction">
                <option value="" disabled selected>Выберите действие</option>
                <optgroup label="Статус">
                    <option value="published">Опубликовать</option>
                    <option value="unpublished">Отправить на модерацию</option>
                </optgroup>
                <optgroup label="Удалить">
                    <option value="delete">Удалить отмеченные</option>
                </optgroup>
            </select>
            <button type="submit" class="btn btn-outline-success" @click="applyMassAction">Применить</button>
        </div>
    </template>
</filterable>
</template>

<script type="text/ecmascript-6">
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
        classState() {
            return (is_approved) => is_approved ?
                'fa fa-check text-success' :
                'fa fa-times text-warning';
        },
    },

    mounted() {
        //
    },

    methods: {
        toggleFilter() {
            this.filterable.active = !this.filterable.active;
        },

        toggleStateComment(row) {
            const state = row.is_approved ? 'unpublished' : 'published';

            this.massUpdate([row.id], state);
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

        applyMassAction() {
            if (!this.selected.length) {
                return this.$notification.warning({
                    message: 'Пожалуйста, выберите Комментарии.'
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

        massUpdate(comments, mass_action) {
            this.$props.model.$massUpdate(comments, mass_action)
                .then((collection) => {
                    this.collection = this.collection.map((comment) => {
                        if (comments.includes(comment.id)) {
                            const updated = collection.find(item => item.id === comment.id);

                            return {
                                ...comment,
                                ...updated
                            };
                        }

                        return comment;
                    });
                });
        },

        massDelete(comments) {
            comments.map(id => this.destroy({
                id
            }));
        },

        destroy(comment) {
            const result = confirm(`Хотите удалить этот Комментарий [${comment.id}] с прикрепленными файлами?`);

            result && this.$props.model.$delete(comment.id)
                .then((response) => {
                    this.collection = this.collection.filter((item) => item.id !== comment.id);
                });
        },
    },
}
</script>
