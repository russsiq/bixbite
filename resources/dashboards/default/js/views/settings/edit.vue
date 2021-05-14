<template>
<div class="row">
    <div class="col col-sm-12">
        <form v-if="showedForm" action="" method="post" @submit.prevent="onSubmit">
            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Блок</div>
                <div class="card-body">

                    <div class="mb-3 row">
                        <label class="col-sm-7 control-label">Поле</label>
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
                                <router-link :to="{ name: 'settings'}" class="btn btn-outline-dark btn-bg-white ms-auto" exact>
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

<script type="text/javascript">
export default {
    name: 'settings-edit',

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
        }
    },

    computed: {
        showedForm() {
            return Object.keys(this.form).length > 0;
        },

        isEditMode() {
            return this.$props.id > 0;
        },

        extensibles() {
            return [];
        },
    },

    async mounted() {
        if (this.isEditMode) {
            this.form = await this.$props.model.$get(this.$props.id);
        }
    },

    methods: {
        async onSubmit(event) {
            if (this.isEditMode) {
                await this.$props.model.$update(this.form.id, {
                    ...this.form
                });
            } else {
                const item = await this.$props.model.$create({
                    ...this.form
                });

                this.form = item;
            }
        },
    },

    async beforeDestroy() {
        await this.model.deleteAll();
    },
}
</script>
