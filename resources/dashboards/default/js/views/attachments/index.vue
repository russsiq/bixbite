<template>
<filterable v-bind="filterable" :value="collection" @apply:change="fetch">
    <template #preaction>
        <div class="btn-group">
            <!--  -->
        </div>
        <div class="btn-group mx-auto">
            <router-link :to="{name: 'files.settings'}" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></router-link>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-dark" @click="toggleFilter"><i class="fa fa-filter"></i></button>
            <button type="button" class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
        </div>
    </template>

    <template #thead>
        <tr>
            <th>#</th>
            <th>Заголовок</th>
            <th>Расширение</th>
            <th colspan="3">Прикрепление файла</th>
            <th>Загрузил</th>
            <th class="text-right d-print-none">Действия</th>
        </tr>
    </template>

    <template #row="{row}">
        <tr :key="row.id">
            <td>{{ row.id }}</td>
            <td><a :href="row.url" target="_blank">{{ row.title }}</a></td>
            <td>{{ row.extension }}</td>
            <template v-if="row.attachable">
                <td>
                    {{ row.attachable_type }}
                </td>
                <td>
                    {{ row.attachable_id }}
                </td>
                <td>
                    <a :href="row.attachable.url" target="_blank">{{ row.attachable.title }}</a>
                </td>
            </template>
            <td v-else colspan="3"><code>Файл не прикреплен</code></td>
            <td>{{ row.user && row.user.name }}</td>
            <td class="text-right d-print-none">
                <div class="btn-group">
                    <button type="button" class="btn btn-link" @click="destroy(row)" :disabled="row.attachable">
                        <i class="fa fa-trash-o" :class="{'text-danger':!row.attachable}"></i>
                    </button>
                </div>
            </td>
        </tr>
    </template>

    <template #tfoot>
        <tr>
            <td>#</td>
            <td>Заголовок</td>
            <td>Расширение</td>
            <td colspan="3">Прикрепление файла</td>
            <td>Загрузил</td>
            <td class="text-right d-print-none">Действия</td>
        </tr>
    </template>
</filterable>
</template>

<script type="text/ecmascript-6">
import {
    mapGetters
} from 'vuex';

import Filterable from '@/views/components/filterable';

export default {
    name: 'attachments',

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
            }
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
        }),
    },

    mounted() {
        this.$props.model.$fetch()
            .then(this.fillTable);
    },

    methods: {
        fetch(filter) {
            this.$props.model.$fetch(filter)
                .then(this.fillTable);
        },

        fillTable(collection) {
            this.collection = collection;
        },

        toggleFilter() {
            this.filterable.active = !this.filterable.active;
        },

        destroy(file) {
            const result = confirm(`Хотите удалить этот файл [${file.title}]?`);

            result && this.$props.model.$delete({
                    params: {
                        id: file.id
                    }
                })
                .then((response) => {
                    this.collection = this.collection.filter((item) => item.id !== file.id);
                });
        },
    },
}
</script>
