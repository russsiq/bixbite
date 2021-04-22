<template>
<div class="row">
    <div class="col col-sm-6">
        <form v-if="showedForm" action="" method="post" @submit.prevent="onSubmit">
            <div class="card card-default">
                <div class="card-header">
                    <i class="fa fa-th-list"></i> Редактирование пользователя <b>{{ form.name }}</b>
                </div>

                <div class="card-body">
                    <div class="mb-3 row">
                        <label class="col-sm-5">Псевдоним</label>
                        <div class="col-sm-7">
                            <input type="text" v-model="form.name" class="form-control" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5">Группа</label>
                        <div class="col-sm-7">
                            <select v-model="form.role" class="form-control">
                                <option v-for="role in roles" :value="role">{{ role | trans }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5">Почта</label>
                        <div class="col-sm-7">
                            <input type="email" v-model="form.email" class="form-control" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5">Пароль</label>
                        <div class="col-sm-7">
                            <input type="password" v-model="form.password" class="form-control" autocomplete="new-password" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5">Подтверждение пароля</label>
                        <div class="col-sm-7">
                            <input type="password" v-model="form.password_confirmation" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                </div>

                <!-- SUBMIT Form -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-7 offset-sm-5">
                            <div class="d-flex">
                                <button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
                                    <span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
                                    <span class="d-none d-md-inline">Сохранить</span>
                                </button>
                                <router-link :to="{name: 'users'}" class="btn btn-outline-dark btn-bg-white ml-auto" exact>
                                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                                    <span class="d-none d-lg-inline">Отменить</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script>
import {
    mapGetters
} from 'vuex';

import ImageUploader from '@/components/image-uploader'

export default {
    name: 'user-edit',

    components: {
        'image-uploader': ImageUploader,
    },

    props: {
        model: {
            type: Function,
            required: true
        },

        id: {
            type: Number,
            required: true
        },
    },

    data() {
        return {
            form: {},
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
        }),

        roles() {
            return this.meta.roles;
        },

        showedForm() {
            return Object.keys(this.form).length > 0;
        },
    },

    mounted() {
        this.$props.model.$get({
            params: {
                id: this.$props.id
            }
        })
        .then(this.fillForm);
    },

    methods: {
        fillForm(data) {
            this.form = Object.assign({}, this.form, {
                id: data.id,
                name: data.name,
                role: data.role,
                email: data.email,
                password: null,
                password_confirmation: null,
            });
        },

        onSubmit(event) {
            this.$notification.warning({
                message: 'This feature is not implemented!'
            });

            // this.model.$update({
            //     params: {
            //         id: this.form.id
            //     },
            //     data: this.form
            // });
        },
    },
}
</script>
