<template>
<form v-if="showedForm" method="post" @submit.prevent="onSubmit" novalidate>
    <ul class="nav nav-tabs">
        <li class="nav-item active"><a href="#pane-images" class="nav-link active" data-toggle="tab">Загрузчик изображений</a></li>
    </ul>

    <br>

    <div class="tab-content">
        <div id="pane-images" class="tab-pane active">
            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Общие параметры</div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Конвертировать изображения</label>
                            <small class="form-text d-block text-muted">Конвертировать загружаемые изображения в формат <code>*.jpeg</code>.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="checkbox" v-model="form.images_is_convert" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Качество изображений</label>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_quality" min="50" max="100" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- <p class="alert alert-info">Нижеуказанные параметры применяются только для изображений, которые используются на странице категории, а также для основного изображения записи. На изображения в тексте записи данные параметры не влияют.</p> -->

            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Миниатюра изображения <code>thumb</code></div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Максимальная ширина</label>
                            <small class="form-text d-block text-muted">При создании миниатюры будет задана эта величина с соблюдением пропорциий.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_thumb_width" min="20" max="240" class="form-control" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Максимальная высота</label>
                            <small class="form-text d-block text-muted">При создании миниатюры будет задана эта величина с соблюдением пропорциий.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_thumb_height" min="20" max="240" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Малое изображение <code>small</code></div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Максимальная ширина</label>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_small_width" min="241" max="576" class="form-control" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Максимальная высота</label>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_small_height" min="241" max="576" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Изображение средних размеров <code>medium</code></div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Максимальная ширина</label>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_medium_width" min="577" max="992" class="form-control" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Максимальная высота</label>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_medium_height" min="577" max="992" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Оригинал изображения</div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Максимальная ширина</label>
                            <small class="form-text d-block text-muted">Оригинал изображения будет уменьшен до этой величины с соблюдением пропорциий.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_max_width" min="993" max="3840" class="form-control" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-7">
                            <label class="control-label">Максимальная высота</label>
                            <small class="form-text d-block text-muted">Оригинал изображения будет уменьшен до этой величины с соблюдением пропорциий.</small>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" v-model.number="form.images_max_height" min="993" max="2160" class="form-control" />
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
                        <router-link :to="{name: entity}" class="btn btn-outline-dark btn-bg-white ms-auto" exact>
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
    name: 'files-settings',

    extends: Module,

    data() {
        return {
            entity: 'files',
        }
    },
}
</script>
