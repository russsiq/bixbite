<template>
<form v-if="showedForm" method="post" @submit.prevent="onSubmit" novalidate>
    <ul class="nav nav-tabs">
        <li class="nav-item active"><a href="#pane-main" class="nav-link active" data-toggle="tab">Основные настройки</a></li>
    </ul>

    <br>

    <div class="tab-content">
        <div id="pane-main" class="tab-pane active">
            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Основные параметры</div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Только для зарегистрированных</label>
                            <small class="form-text text-muted">Да - комментирование доступно только для зарегистрированных пользователей.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="checkbox" v-model="form.regonly" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Модерация комментариев</label>
                            <small class="form-text text-muted">Опубликовывать комментарии только после одобрения.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="checkbox" v-model="form.moderate" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Параметры отображения</div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Вывод древовидных комментариев</label>
                            <small class="form-text text-muted">Да - постраничная навигация будет недоступна.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="checkbox" v-model="form.nested" />
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
</template>

<script type="text/ecmascript-6">
import Module from '@/views/settings/module.js';

export default {
    name: 'comments-settings',

    extends: Module,

    data() {
        return {
            entity: 'comments',
        }
    },
}
</script>
