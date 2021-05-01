<template>
<div class="">
    <div v-if="image.id" class="image__preview">
        <img :src="image.url" />

        <div class="btn-toolbar">
            <button type="button" class="btn btn-sm btn-outline-secondary" @click.prevent="editImage"><i class="fa fa-pencil"></i></button>
            <button type="button" class="btn btn-sm btn-outline-secondary" @click.prevent="deleteImage"><i class="fa fa-trash"></i></button>
        </div>
    </div>

    <modal v-if="modalShown" @close="">
        <template slot="modal__header">Редактирование изображения</template>

        <template slot="modal__body">
            <div class="mb-3 has-float-label">
                <label class="control-label">Заголовок</label>
                <input type="text" v-model="image.title" @keydown.13.prevent class="form-control" autocomplete="off" required />
            </div>

            <div class="mb-3 has-float-label">
                <label class="control-label">Описание</label>
                <textarea v-model="image.description" @keydown.13.prevent class="form-control noresize" rows="4"></textarea>
            </div>
        </template>

        <template slot="modal__footer">
            <button type="button" class="btn btn-outline-success mr-2" @click="updateImage">Сохранить</button>
            <button type="button" class="btn btn-outline-secondary" @click="closeModal">Отменить</button>
        </template>
    </modal>
</div>
</template>

<script type="text/ecmascript-6">
import Modal from 'bxb-modal';

import Attachment from '@/store/models/attachment';

export default {
    name: 'image-preview',

    components: {
        'modal': Modal,
    },

    props: {
        image_id: {
            type: Number,
            default: null
        }
    },

    data() {
        return {
            image: {},
            modalShown: false,
        }
    },

    computed: {},

    watch: {
        'image_id': {
            immediate: true,
            handler: function(id, oldVal) {
                this.image = {};

                if ('number' === typeof id) {
                    Attachment.$get({
                            params: {
                                id: id
                            }
                        })
                        .then(this.fillForm);
                }
            }
        },
    },

    methods: {
        /**
         * Заполнить форму полученными данными.
         * @param {Attachment} image
         */
        fillForm(image) {
            this.image = image;
        },

        /**
         * Open the modal to edit image attributes.
         */
        editImage() {
            this.modalShown = true;
        },

        /**
         * Add a new title and description to image.
         */
        updateImage() {
            Attachment.$update({
                    params: {
                        id: this.image_id
                    },

                    data: {
                        title: this.image.title,
                        description: this.image.description,
                    }
                })
                .then(response => {
                    console.log(response);
                    this.closeModal();
                });
        },

        deleteImage() {
            const result = confirm(`Хотите удалить этот файл [${this.image.title}] с сервера?`);

            result && Attachment.$delete({
                    params: {
                        id: this.image_id
                    },
                })
                .then(response => {
                    this.$emit('destroy');
                });
        },

        closeModal() {
            this.modalShown = false;
        },
    }
}
</script>

<style lang="scss" scoped>
.image__preview {
    box-shadow: 0 8px 12px 0 rgba(0, 0, 0, .25);
    color: #43ac6a;
    display: block;
    min-height: 40px;
    // padding: .25rem;
    // border: 1px solid rgba(0, 0, 0, .125);
    // display: inline-block;
    overflow: hidden;
    position: relative;
}

.image__preview .btn-toolbar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between!important;
    padding: 0.25rem;
    position: absolute;
    top: 0;
    width: 100%;
}
</style>
