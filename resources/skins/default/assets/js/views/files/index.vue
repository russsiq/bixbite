<template>
<filterable v-bind="filterable">
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
            <th>Прикрепление файла</th>
            <th>Загрузил</th>
            <th class="text-right d-print-none">Действия</th>
        </tr>
    </template>

    <template #row="{row}">
        <tr :key="row.id">
            <td>{{ row.id }}</td>
            <td><a :href="row.url" target="_blank">{{ row.title }}</a></td>
            <td>{{ row.extension }}</td>
            <td v-if="row.attachment"><a :href="row.attachment.url" target="_blank">{{ row.attachment.title }}</a></td>
            <td v-else><code>Файл не прикреплен</code></td>
            <td>{{ row.user && row.user.name }}</td>
            <td class="text-right d-print-none">
                <div class="btn-group">
                    <button type="button" class="btn btn-link" @click="destroy(row)" :disabled="row.attachment">
                        <i class="fa fa-trash-o" :class="{'text-danger':!row.attachment}"></i>
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
            <td>Прикрепление файла</td>
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
    name: 'files',

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
        //
    },

    methods: {
        toggleFilter() {
            this.filterable.active = !this.filterable.active;
        },

        /**
         * Delete the article.
         */
        destroy(file) {
            const result = confirm(`Вы точно хотите удалить этот файл: ${file.title}?`);

            result && this.$props.model.$delete({
                params: {
                    id: file.id
                }
            });
        }
    },
}
</script>
