<template>
<div class="row">
    <div class="col col-sm-12">
        <form v-if="showedForm" action="" method="post" @submit.prevent="onSubmit">
            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Параметры поля</div>
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-7 control-label">Расширяемая таблица<small class="form-text text-muted">К этой таблице в БД будет добавлено поле.</small></label>
                        <div class="col-sm-5">
                            <template v-if="isEditMode">
                                <input type="text" v-model="form.extensible" class="form-control" required readonly />
                            </template>
                            <template v-else>
                                <select v-model="form.extensible" class="form-control" required>
                                    <option v-for="extensible in extensibles" :value="extensible">{{ extensible }}</option>
                                </select>
                            </template>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-7 control-label">Тип<small class="form-text text-muted">Тип данных, хранимых в текущем поле.</small></label>
                        <div class="col-sm-5">
                            <template v-if="isEditMode">
                                <input type="text" v-model="form.type" class="form-control" required readonly />
                            </template>
                            <template v-else>
                                <select v-model="form.type" class="form-control" required>
                                    <option v-for="type in fieldTypes" :value="type">{{ type }}</option>
                                </select>
                            </template>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-7 control-label">Идентификатор<small class="form-text text-muted">Заполняется по схеме <code>/^[a-z_]+$/</code>. В БД к полю будет добавлен префикс <code>x_</code>.</small></label>
                        <div class="col-sm-5">
                            <input type="text" v-model="form.name" class="form-control" required :readonly="isEditMode" />
                        </div>
                    </div>

                    <div v-if="isArrayType" class="form-group row">
                        <label class="col-sm-7 control-label">Список пар <small class="form-text text-muted">Список пар <u>ключ => значение</u> для доп. поля типа <b>array</b>.</small></label>
                        <div class="col-sm-5">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Ключ</th>
                                        <th>Значение</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(param, index) in form.params">
                                        <td><input type="text" v-model="param.key" class="form-control form-control-sm" /></td>
                                        <td><input type="text" v-model="param.value" class="form-control form-control-sm" /></td>
                                        <td><button type="button" @click="delParam(index)" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Добавить пару</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" v-model="param.key" class="form-control form-control-sm" @keydown.enter.prevent="addParam" /></td>
                                        <td><input type="text" v-model="param.value" class="form-control form-control-sm" @keydown.enter.prevent="addParam" /></td>
                                        <td><button type="button" @click="addParam" class="btn btn-sm btn-outline-success"><i class="fa fa-plus"></i></button></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div v-if="isArrayType" class="form-group row">
                        <label class="col-sm-7 control-label">Предварительный просмотр
                            <small class="form-text text-muted">Так будет выглядеть дополнительное поле, т.е. в форме выпадающего списка.</small>
                        </label>
                        <div class="col-sm-5">
                            <select class="form-control">
                                <option v-for="(param, index) in form.params" :value="param.key">{{ param.value }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Параметры отображения при заполнении</div>
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-7 control-label">Название</label>
                        <div class="col-sm-5">
                            <input type="text" v-model="form.title" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-7 control-label">Краткое описание</label>
                        <div class="col-sm-5">
                            <textarea v-model="form.descr" rows="4" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-7 control-label">Атрибуты <small class="form-text text-muted">Например, <code><b>required</b></code>, <code>disabled</code>, <code>placeholder</code>, <code>onclick</code>, <code>autocomplete="off"</code>, <code>style</code>, <code>rows</code> и т.д.<br>Записываются в одну строку. Валидацию на правильность написания не проходят, передаются как есть.</small></label>
                        <div class="col-sm-5">
                            <textarea v-model="form.html_flags" rows="4" class="form-control"></textarea>
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
                                <router-link :to="{ name: 'x_fields'}" class="btn btn-outline-dark btn-bg-white ml-auto" exact>
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
import {
    mapGetters,
    mapActions
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
            form: new this.$props.model,
            param: {
                key: null,
                value: null
            }
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

    async mounted() {
        if (this.isEditMode) {
            this.form = await this.model.$get({
                params: {
                    id: this.$props.id
                }
            });
        }

        // При вложенности свойств Vuex-ORM ругается:
        // Error: [vuex] do not mutate vuex store state outside mutation handlers.
        this.form.params && (this.form.params = [...this.form.params]);
    },

    methods: {
        addParam(event) {
            this.form.params.push(this.param);
            this.resetParam();
        },

        delParam(index) {
            this.form.params.splice(index, 1);
        },

        resetParam() {
            this.param.key = null;
            this.param.value = null;
        },

        async onSubmit(event) {
            if (this.isEditMode) {
                await this.$props.model.$update({
                    params: {
                        id: this.form.id
                    },
                    data: this.form
                });
            } else {
                const item = await this.$props.model.$create({
                    data: this.form
                });

                this.form = item;
            }
        },
    },

    async beforeDestroy() {
        await this.$props.model.deleteAll();
    }
}
</script>
