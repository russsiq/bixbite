<template>
<filterable v-bind="filterable" :value="collection" @apply:change="fetch">
    <template #preaction>
        <router-link :to="{name: 'x_fields.create'}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Создать</router-link>
        <div class="btn-group d-flex mx-auto">
            <a :href="url('app_common/clearcache/x_fields')" title="Очистить кэш" class="btn btn-outline-dark"><i class="fa fa-recycle"></i></a>
        </div>
        <div class="btn-group">
            <button type="button" title="Печать" class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
        </div>
    </template>

    <template #thead>
        <tr>
            <th>#</th>
            <th>Идентификатор</th>
            <th>Расширяемая таблица</th>
            <th>Тип поля</th>
            <th>Название</th>
            <th class="text-right d-print-none">Действие</th>
        </tr>
    </template>

    <template #row="{row}">
        <tr :key="row.id">
            <td>{{ row.id }}</td>
            <td>
                <router-link :to="{name: 'x_fields.edit', params:{id: row.id}}">{{ row.name }}</router-link>
            </td>
            <td>{{ row.extensible }}</td>
            <td>{{ row.type }}</td>
            <td>{{ row.title }}</td>
            <td class="text-right d-print-none">
                <div class="btn-group">
                    <router-link :to="{name: 'x_fields.edit', params:{id: row.id}}" class="btn btn-link"><i class="fa fa-pencil"></i></router-link>
                    <button type="button" class="btn btn-link" @click="destroy(row)"><i class="fa fa-trash-o text-danger"></i></button>
                </div>
            </td>
        </tr>
    </template>

    <template #tfoot>
        <tr>
            <td>#</td>
            <td>Идентификатор</td>
            <td>Расширяемая таблица</td>
            <td>Тип поля</td>
            <td>Название</td>
            <td class="text-right d-print-none">Действие</td>
        </tr>
    </template>

    <template #action></template>
</filterable>
</template>

<script type="text/ecmascript-6">
import Filterable from '@/views/components/filterable';

export default {
    name: 'x_fields',

    components: {
        'filterable': Filterable
    },

    props: {
        model: {
            type: Function,
            required: true
        },
    },

    data() {
        return {
            collection: [],
            filterable: {
                model: this.$props.model,
                active: false,
                massAction: false,
            },
        }
    },

    async mounted() {
        await this.loadFromJsonPath('x_fields');
    },

    methods: {
        fetch(filter) {
            this.$props.model.$fetch(filter)
                .then(this.fillTable);
        },

        fillTable(collection) {
            this.collection = collection;
        },

        destroy(field) {
            const result = confirm(
                `Хотите безвозвратно удалить поле [${field.title}] \n из таблицы [${field.extensible}] со всеми связанными данными?`
            );

            result && this.$props.model.$delete({
                params: {
                    id: field.id
                }
            });
        }
    },
};
</script>
