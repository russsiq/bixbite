<template>
<filterable v-bind="filterable" :value="collection" @apply:change="fetch">
    <template #preaction>
        <div class="btn-group d-flex ms-auto me-auto">
            <router-link :to="{name: 'privileges'}" class="btn btn-outline-dark" title="Привилегии"><i class="fa fa-user-secret"></i></router-link>
            <router-link :to="{name: 'x_fields'}" class="btn btn-outline-dark" title="Дополнительные поля"><i class="as-icon">χφ</i></router-link>
            <router-link :to="{ name: 'users.settings'}" class="btn btn-outline-dark" title="Настройки"><i class="fa fa-cogs"></i></router-link>
        </div>
        <!-- НЕЛЬЗЯ ОСТАВЛЯТЬ ССЫЛКУ НА ПЕЧАТЬ ИНФОРМАЦИИ О ПОЛЬЗОВАТЕЛЯХ -->
    </template>

    <template #thead>
        <tr>
            <th>#</th>
            <th>Имя</th>
            <th>Группа</th>
            <th class="d-print-none">Email</th>
            <th class="hidden-xs">Зарегистрирован</th>
            <th class="hidden-xs">Был активен</th>
            <th><i class="fa fa-newspaper-o"></i></th>
            <th><i class="fa fa-comments-o"></i></th>
            <th class="text-right d-print-none">Действия</th>
        </tr>
    </template>

    <template #row="{row}">
        <tr>
            <td>{{ row.id }}</td>
            <td>
                <b :class="isOnline(row.is_online)">&nbsp;•&nbsp;</b>
                <router-link :to="{name: 'user.edit', params: row}">{{ row.name }}</router-link>
            </td>
            <td>{{ row.role | trans }}</td>
            <td class="d-print-none">{{ row.email }}</td>
            <td class="hidden-xs">{{ row.created_at | dateToString }}</td>
            <td class="hidden-xs" v-if="row.logined_at">{{ row.logined_at | dateToString }}</td>
            <td v-else>Не заходил</td>
            <td>{{ row.articles_count }}</td>
            <td>{{ row.comments_count }}</td>
            <td class="text-right d-print-none">
                <div class="btn-group">
                    <!-- <button type="button" class="btn btn-link" @click="toggleIsActive(row)"><i :class="isActive(row)"></i></button> -->
                    <router-link :to="{name: 'user.edit', params: row}" class="btn btn-link"><i class="fa fa-pencil"></i></router-link>
                    <button type="button" class="btn btn-link" @click.prevent="destroy(row)"><i class="fa fa-trash-o text-danger"></i></button>
                </div>
            </td>
        </tr>
    </template>

    <template #tfoot>
        <tr>
            <td>#</td>
            <td>Имя</td>
            <td>Группа</td>
            <td class="d-print-none">Email</td>
            <td class="hidden-xs">Зарегистрирован</td>
            <td class="hidden-xs">Был активен</td>
            <td><i class="fa fa-newspaper-o"></i></td>
            <td><i class="fa fa-comments-o"></i></td>
            <td class="text-right d-print-none">Действия</td>
        </tr>
    </template>

    <template #action></template>
</filterable>
</template>

<script type="text/ecmascript-6">
import Filterable from '@/views/components/filterable'

export default {
    name: 'users',

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

    computed: {
        isOnline() {
            return (is_online) => is_online ?
                'text-success' :
                'text-warning';
        },

        isActive() {
            return (email_verified_at) => email_verified_at ?
                'fa fa-times text-warning' :
                'fa fa-check text-success';
        }
    },

    async mounted() {
        await this.loadFromJsonPath('users');

    },

    methods: {
        fetch(filter) {
            this.$props.model.$fetch(filter)
                .then(this.fillTable);
        },

        fillTable(collection) {
            this.collection = collection;
        },

        toggleIsActive(row) {
            console.log(row);
        },

        destroy(user) {
            const result = confirm(`Хотите удалить этого Пользователя [${user.name}]?`);

            result && this.$props.model.$delete({
                    params: {
                        id: user.id
                    }
                })
                .then((response) => {
                    this.collection = this.collection.filter((item) => item.id !== user.id);
                });
        }
    },
}
</script>
