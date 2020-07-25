<template>
<form v-if="showedForm" action="" method="post" @submit.prevent="save" @keydown.ctrl.83.prevent="save">
    <div class="card card-default">
        <div class="card-header"><i class="fa fa-th-list"></i> Основное содержание</div>
        <div class="card-body">

            <div class="mb-3 row">
                <div class="col-sm-7"><label class="control-label">Заголовок</label></div>
                <div class="col-sm-5">
                    <input type="text" v-model="note.title" class="form-control" required />
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7"><label class="control-label">Краткое описание</label></div>
                <div class="col-sm-5">
                    <textarea v-model="note.description" rows="4" class="form-control" required></textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-7">
                    <label class="control-label">Прикрепленное изображение</label>
                    <small class="form-text text-muted">Вы можете прикрепить изображение непосредственно к заметке.</small>
                </div>
                <div class="col-sm-5">
                    <image-uploader :value="note.image_id" @update:image_id="sync('image_id', $event)"></image-uploader>
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
            // Текущая Заметка (редактируемая или вновь созданная).
            note: {
                title: '',
                description: '',

            },
        }
    },

    computed: {
        /**
         * Разрешено ли отобразить форму создания/редактирования.
         * @return {Boolean}
         */
        showedForm() {
            return Object.keys(this.note).length > 0;
        },

        /**
         * Если текущий режим – режим редактирования Заметки.
         * @return {Boolean}
         */
        isEditMode() {
            return this.$props.id > 0;
        },
    },

    mounted() {
        // Если это режим редактирования, то необходимо подгрузить Заметку.
        this.isEditMode && this.$props.model.$get({
                params: {
                    id: this.$props.id
                }
            })
            .then(this.fillForm);
    },

    methods: {
        fillForm(note) {
            this.note = Object.assign({}, this.note, note);
        },

        sync(attribute, value) {
            this.update(attribute, value)
                .save();
        },

        update(attribute, value) {
            this.note[attribute] = value;

            return this;
        },

        save() {
            if (this.isEditMode) {
                return this.$props.model.$update({
                        params: {
                            id: this.$props.id
                        },

                        data: {
                            ...this.note
                        }
                    })
                    .then(this.fillForm);
            } else {
                this.$props.model.$create({
                        data: {
                            ...this.note
                        }
                    })
                    .then(this.fillForm);
            }
        },
    },
}
</script>
