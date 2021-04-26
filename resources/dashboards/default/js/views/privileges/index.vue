<template>
<div class="card card-table">
    <div class="card-header d-flex d-print-none">
        <div class="btn-group d-flex ml-auto">
            <a :href="url('app_common/clearcache/roles|privileges')" title="Очистить кэш" class="btn btn-outline-dark"><i class="fa fa-recycle"></i></a>
        </div>
        <div class="btn-group d-flex ml-auto"></div>
    </div>

    <form action="#" @submit.prevent="save">
        <div class="card-body table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Привилегия</th>
                        <th>Описание</th>
                        <template v-for="(role, index) in roles">
                            <th>
                                <label class="control-label">
                                    <input type="checkbox" v-if="'owner' !== role" @click="updateColumn(role, $event)" /> {{ role }}
                                </label>
                            </th>
                        </template>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, index) in privileges">
                        <td>
                            <label class="control-label">
                                <input type="checkbox" @click="updateRow(index, $event)" />&nbsp; {{ item.privilege }}
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
export default {
    name: 'privileges',

    data() {
        return {
            privileges: [],
            roles: []
        }
    },

    props: {
        model: {
            type: Function,
            required: true
        },
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

    mounted() {
        this.$props.model.$fetch()
            .then(this.fillTable);
    },

    methods: {
        fillTable(data) {
            this.roles = data.roles;
            this.privileges = data.privileges;
        },

        save(event) {
            const result = confirm('Вы уверены?');

            result && this.$props.model.$create({
                    data: this.table
                })
                .then(this.fillTable);
        },

        updateColumn(role, event) {
            if ('owner' !== role) {
                this.privileges.forEach(item => {
                    item[role] = event.target.checked;
                });
            }
        },

        updateRow(index, event) {
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
