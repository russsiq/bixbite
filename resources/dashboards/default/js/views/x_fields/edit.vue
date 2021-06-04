<template>
<form v-if="showedForm" action="" method="post" @submit.prevent="save" @keydown.ctrl.83.prevent="save">
    <div class="card card-default">
        <div class="card-header"><i class="fa fa-th-list"></i> Параметры поля</div>
        <div class="card-body">

            <div class="mb-3 row">
                <label class="col-sm-7 control-label">
                    Расширяемая таблица
                    <small class="form-text d-block text-muted">К этой таблице в БД будет добавлено поле.</small>
                </label>
                <div class="col-sm-5">
                    <template v-if="isEditMode">
                        <input type="text" v-model="form.extensible" class="form-control" required readonly />
                    </template>
                    <template v-else>
                        <select class="form-select" v-model="form.extensible" required>
                            <option v-for="(extensible, index) in extensibles" :key="index" :value="extensible">{{ extensible }}</option>
                        </select>
                    </template>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-7 control-label">Идентификатор<small class="form-text d-block text-muted">Заполняется по схеме <code>/^[a-z0-9_]+$/</code>. В БД к полю будет добавлен префикс <code>x_</code>.</small></label>
                <div class="col-sm-5">
                    <input type="text" v-model="form.name" class="form-control" required :readonly="isEditMode" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-7 control-label">Тип<small class="form-text d-block text-muted">Тип данных, хранимых в текущем поле.</small></label>
                <div class="col-sm-5">
                    <template v-if="isEditMode">
                        <input type="text" v-model="form.type" class="form-control" required readonly />
                    </template>
                    <template v-else>
                        <select class="form-select" v-model="form.type" required>
                            <option v-for="(type, index) in fieldTypes" :key="index" :value="type">{{ type }}</option>
                        </select>
                    </template>
                </div>
            </div>

            <template v-if="isArrayType">
                <div class="mb-3 row">
                    <label class="col-sm-7 control-label">
                        Список пар
                        <small class="form-text d-block text-muted">Список пар <u>ключ => значение</u> для доп. поля типа <b>array</b>.</small>
                    </label>
                    <div class="col-sm-5">
                        <table v-if="form.params.length" class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Ключ</th>
                                    <th>Значение</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(param, index) in form.params" :key="index">
                                    <td><input type="text" v-model="param.key" class="form-control form-control-sm" /></td>
                                    <td><input type="text" v-model="param.value" class="form-control form-control-sm" /></td>
                                    <td>
                                        <button type="button" @click="delParam(index)" class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" @click="addParam" class="btn btn-sm btn-outline-success">Добавить пару</button>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-7 control-label">Предварительный просмотр
                        <small class="form-text d-block text-muted">Так будет выглядеть дополнительное поле, т.е. в форме выпадающего списка.</small>
                    </label>
                    <div class="col-sm-5">
                        <select class="form-select">
                            <option v-for="(param, index) in form.params" :key="index" :value="param.key">{{ param.value }}</option>
                        </select>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header"><i class="fa fa-th-list"></i> Параметры отображения при заполнении</div>
        <div class="card-body">

            <div class="mb-3 row">
                <label class="col-sm-7 control-label">Название</label>
                <div class="col-sm-5">
                    <input type="text" v-model="form.title" class="form-control" required />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-7 control-label">Краткое описание</label>
                <div class="col-sm-5">
                    <textarea v-model="form.description" rows="4" class="form-control"></textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-7 control-label">Атрибуты <small class="form-text d-block text-muted">Например, <code><b>required</b></code>, <code>disabled</code>, <code>placeholder</code>, <code>onclick</code>, <code>autocomplete="off"</code>, <code>style</code>, <code>rows</code> и т.д.<br>Каждый атрибут и его возможное значение записывается с новой строки.<br>Валидацию на правильность написания не проходят, передаются как есть.</small></label>
                <div class="col-sm-5">
                    <!-- <textarea v-model="form.html_flags" rows="4" class="form-control"></textarea> -->

                    <table v-if="form.html_flags" class="table table-sm">
                        <thead>
                            <tr>
                                <th>Атрибут</th>
                                <th>Значение</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(attribute, index) in form.html_flags" :key="index">
                                <td><input type="text" v-model="attribute.key" class="form-control form-control-sm" /></td>
                                <td><input type="text" v-model="attribute.value" class="form-control form-control-sm" /></td>
                                <td>
                                    <button type="button" @click="delHtmlFlag(index)" class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" @click="addHtmlFlag" class="btn btn-sm btn-outline-success">Добавить пару</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-footer">
            <div class="row">
                <div class="col-sm-5 offset-sm-7">
                    <div class="d-flex">
                        <button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
                            <span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
                            <span class="d-none d-md-inline">Сохранить</span>
                        </button>
                        <router-link :to="{ name: 'x_fields'}" class="btn btn-outline-dark btn-bg-white ms-auto" exact>
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
import {
    mapGetters
} from 'vuex';

export default {
    name: 'x_fields-edit',

    components: {
        //
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
            form: {
                name: null,
                params: [],
                html_flags: [],
            },
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
        }),

        extensibles() {
            // return this.meta.extensibles;
            return this.$props.model.state().extensibles;
        },

        fieldTypes() {
            // return this.meta.field_types;
            return this.$props.model.state().field_types;
        },

        showedForm() {
            return Object.keys(this.form).length > 0;
        },

        isEditMode() {
            return this.$props.id > 0;
        },

        isArrayType() {
            return 'array' === this.form.type;
        },
    },

    mounted() {
        this.isEditMode && this.$props.model.$get(this.$props.id)
            .then(this.fillForm);
    },

    methods: {
        fillForm(form) {
            this.form = Object.assign({}, this.form, form);
        },

        addParam(event) {
            this.form.params.push({
                key: null,
                value: null,
            });
        },

        delParam(index) {
            this.form.params.splice(index, 1);
        },

        addHtmlFlag(event) {
            this.form.html_flags.push({
                key: null,
                value: '',
            });
        },

        delHtmlFlag(index) {
            this.form.html_flags.splice(index, 1);
        },

        save() {
            if (this.isEditMode) {
                return this.$props.model.$update(this.$props.id, {
                        ...this.form
                    })
                    .then(this.fillForm);
            }

            this.$props.model.$create({
                    ...this.form
                })
                .then(this.fillForm);
        },
    },
}
</script>
