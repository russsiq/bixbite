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
                <div id="pane-main" class="tab-pane">
                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Блок настроек</div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-sm-7 control-label">Пример настройки <small class="form-text text-muted">Описание настройки.</small></label>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.example" class="form-control" />
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
    name: 'categories-settings',

    extends: Module,

    data() {
        return {
            entity: 'categories',
        }
    },
}
</script>
