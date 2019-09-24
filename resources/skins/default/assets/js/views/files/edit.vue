<template>
<div class="row">
    <div class="col col-sm-12">
        <form v-if="showedForm" action="" method="post" @submit.prevent="onSubmit">
            <div class="card card-default">
                <div class="card-header"><i class="fa fa-th-list"></i> Параметры отображения при заполнении</div>
                <div class="card-body">

                    <div class="form-group row">
                        <div class="col-sm-7"><label class="control-label">Заголовок</label></div>
                        <div class="col-sm-5">
                            <input type="text" v-model="form.title" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7"><label class="control-label">Краткое описание</label></div>
                        <div class="col-sm-5">
                            <textarea v-model="form.description" rows="4" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-7">
                            <label class="control-label">Прикрепленное изображение</label>
                            <small class="form-text text-muted">Вы можете прикрепить изображение непосредственно к заметке.</small>
                        </div>
                        <div class="col-sm-5">
                            <image-uploader v-model.number="form.image_id"></image-uploader>
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
                                <router-link :to="{name: 'notes'}" class="btn btn-outline-dark btn-bg-white ml-auto" exact>
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
import ImageUploader from '@/components/image-uploader';

export default {
    name: 'notes-edit',

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
            form: new this.$props.model,
            param: {
                key: null,
                value: null
            },
        }
    },

    computed: {
        showedForm() {
            return Object.keys(this.form).length > 0;
        },

        isEditMode() {
            return this.$props.id > 0;
        },
    },

    async mounted() {
        this.form = await this.$props.model.$get({
            params: {
                id: this.isEditMode ? this.$props.id : 'form'
            }
        });
    },

    methods: {
        async onSubmit(event) {
            if (this.isEditMode) {
                await this.$props.model.$update({
                    params: {
                        id: this.form.id
                    },

                    data: {
                        ...this.form
                    },

                    update: ['files']
                });
            } else {
                this.form = await this.$props.model.$create({
                    data: {
                        ...this.form
                    },

                    update: ['files']
                });
            }
        },
    },
}
</script>
