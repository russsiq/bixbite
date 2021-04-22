<template>
<filterable v-bind="filterable">
    <template #preaction>
        <router-link :to="{name: 'settings.create'}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Создать</router-link>
        <div class="btn-group d-flex ml-auto">
            <a :href="url('app_common/clearcache/settings')" title="Очистить кэш" class="btn btn-outline-dark"><i class="fa fa-recycle"></i></a>
        </div>
        <div class="btn-group ml-auto">
            <a href="#" title="Печать" class="btn btn-outline-dark" onclick="window.print(); return false;"><i class="fa fa-print"></i></a>
        </div>
    </template>

    <template #thead>
        <tr>
            <th>#</th>
            <th>Настройка</th>
            <th>Тип поля</th>
            <th>Заголовок</th>
            <th>Модуль</th>
            <th>Вкладка</th>
            <th>Набор полей</th>
            <th class="text-right d-print-none">Действие</th>
        </tr>
    </template>

    <template #row="{row}">
        <tr>
            <td>{{ row.id }}</td>
            <td>
                <router-link :to="{name: 'settings.edit', params:{id: row.id}}">{{ row.name }}</router-link>
            </td>
            <td>{{ row.type }}</td>
            <td>{{ row.title | trans }}</td>
            <td>{{ row.module_name }}</td>
            <td>{{ row.section }}</td>
            <td>{{ row.fieldset }}</td>
            <td class="text-right">
                <div class="btn-group">
                    <router-link :to="{name: 'settings.edit', params:{id: row.id}}" :title="'Edit' | trans" class="btn btn-link"><i class="fa fa-pencil"></i></router-link>
                    <button type="button" :title="'Delete' | trans" class="btn btn-link" @click.prevent="destroy(row)">
                        <i class="fa fa-trash-o text-danger"></i>
                    </button>
                </div>
            </td>
        </tr>
    </template>

    <template #tfoot>
        <tr>
            <td>#</td>
            <td>Настройка</td>
            <td>Тип поля</td>
            <td>Заголовок</td>
            <td>Модуль</td>
            <td>Вкладка</td>
            <td>Набор полей</td>
            <td class="text-right d-print-none">Действие</td>
        </tr>
    </template>

    <template #action></template>
</filterable>
</template>

<script type="text/ecmascript-6">
import Filterable from '@/views/components/filterable'

export default {
    name: 'settings',

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
            filterable: {
                model: this.$props.model,
                active: false,
            },
        }
    },

    computed: {
        collection() {
            return this.$props.model.all();
        },
        modules() {
            const [...modules] = new Set(
                this.collection.map(item => item.module_name)
            );

            return modules;
        }
    },

    watch: {
        modules(modules) {
            modules.map(module => this.loadFromJsonPath(module));
        }
    },

    mounted() {
        //
    },

    methods: {
        destroy(row) {
            const result = confirm(`Вы уверены, что хотите удалить эту настройку: ${row.name}?`);

            result && this.$props.model.$delete({
                params: {
                    id: row.id
                }
            });
        }
    },
}
</script>
