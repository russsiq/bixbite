<template>
<div class="row">
    <div class="col col-sm-12">
        <form v-if="showedForm" method="post" @submit.prevent="onSubmit" novalidate>
            <ul class="nav nav-tabs">
                <li class="nav-item active">
                    <a href="#pane-main" class="nav-link active" data-toggle="tab">Основные настройки</a>
                </li>
            </ul>

            <br>

            <div class="tab-content">
                <div id="pane-main" class="tab-pane active">
                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Основные параметры</div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Разрешить регистрацию</label>
                                    <small class="form-text text-muted">Разрешить новым пользователям регистрироваться на сайте.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.auth_allow_register" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Идентификация пользователя</label>
                                    <small class="form-text text-muted">Укажите, что для входа на сайт помимо пароля требуется ввести пользователю.</small>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.auth_username" class="form-control">
                                        <option value="name">Псевдоним</option>
                                        <option value="email">Почта</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Аватар</div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Максимальная ширина</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model.number="form.avatar_max_width" min="20" class="form-control" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Максимальная высота</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model.number="form.avatar_max_height" min="20" class="form-control" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Использовать сервис <i>gravatar.com</i></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.gravatar_used" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-5 offset-sm-7">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-outline-success btn-bg-white">
                                    <span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
                                    <span class="d-none d-md-inline">Сохранить</span>
                                </button>
                                <router-link :to="{name: entity}" class="btn btn-outline-dark btn-bg-white ml-auto" exact>
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

<script type="text/ecmascript-6">
import Module from '@/views/settings/module.js';

export default {
    name: 'users-settings',

    extends: Module,

    data() {
        return {
            entity: 'users',
        }
    },
}
</script>
