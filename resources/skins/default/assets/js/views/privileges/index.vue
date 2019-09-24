<template>
<div class="card card-table">
    <div class="card-header d-flex d-print-none">
        <div class="btn-group d-flex ml-auto">
            <a :href="url('app_common/clearcache/roles|privileges')" title="Очистить кэш" class="btn btn-outline-dark"><i class="fa fa-recycle"></i></a>
        </div>
        <div class="btn-group d-flex ml-auto"></div>
    </div>

    <form action="#" @submit.prevent="onSubmit">
        <div class="card-body table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Привилегия</th>
                        <th>Описание</th>
                        <template v-for="(role, index) in roles">
                            <th>
                                <label class="control-label">
                                    <input type="checkbox" v-if="'owner' !== role" @click="updateColumn($event, role)" /> {{ role }}
                                </label>
                            </th>
                        </template>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, index) in privileges">
                        <td>
                            <label class="control-label">
                                <input type="checkbox" @click="updateRow($event, index)" />&nbsp; {{ item.privilege }}
                            </label>
                        </td>
                        <td>{{ item.description }}</td>
                        <td v-for="role in roles">
                            <label class="control-label">
                                <input type="checkbox" v-model="item[role]" :disabled="'owner' == role" />
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
                <span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
                <span class="d-none d-md-inline">Сохранить</span>
            </button>
            <router-link :to="{name: 'users'}" class="btn btn-outline-dark btn-bg-white ml-auto" exact>
                <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                <span class="d-none d-lg-inline">Отменить</span>
            </router-link>
        </div>
    </form>
</div>
</template>

<script type="text/ecmascript-6">
import {
    get,
    post
} from '@/helpers/api'

export default {
    name: 'privileges',

    data() {
        return {
            privileges: [],
            roles: []
        }
    },

    computed: {
        table() {
            const table = {};

            this.roles.map(role => {
                table[role] = [];

                this.privileges.map(privilege => {
                    if (privilege[role]) {
                        table[role].push(privilege.id);
                    }
                });
            });

            return table;
        }
    },

    async mounted() {
        try {
            const response = await get(`${Pageinfo.api_url}/privileges`);

            this.roles = response.data.roles;
            this.privileges = response.data.privileges;
        } catch (e) {
            console.error('Не удалось загрузить привилегии пользователей.');
        }
    },

    methods: {
        async onSubmit(event) {
            if (!confirm('Вы уверены?')) return false;

            try {
                const response = await post(`${Pageinfo.api_url}/privileges`, this.table);

                this.roles = response.data.roles;
                this.privileges = response.data.privileges;

                Notification.success({
                    message: __('Updated!'),
                });
            } catch (e) {
                console.error('Не удалось сохранить привилегии пользователей.');
            }
        },

        updateColumn(event, role) {
            if ('owner' !== role) {
                this.privileges.forEach(item => {
                    item[role] = event.target.checked;
                });
            }
        },

        updateRow(event, index) {
            this.roles.forEach(role => {
                if ('owner' !== role) {
                    this.privileges[index][role] = event.target.checked;
                }
            });
        },
    }
}
</script>

<style scoped>
label {
    cursor: pointer;
    margin-bottom: 0;
}
</style>
