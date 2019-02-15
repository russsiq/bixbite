<template>
<div class="">
    <label class="image-uploader" :for="'image__uploader_'+_uid">
        <div v-if="'uploaded' == state" class="image-uploader-success">
            <input type="hidden" :name="input_name" :value="image.id" />
            <img :src="image.url" alt="image" />

            <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" @click.prevent="editImage()"><i class="fa fa-pencil"></i></button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" @click.prevent="deleteImage()"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div>
        <div v-else class="image-uploader-variant">
            <div v-if="'uploading' == state" class="text-primary"><i class="fa fa-4x fa-spinner fa-spin"></i></div>
            <div v-else-if="'error' == state" class="text-danger"><i class="fa fa-4x fa-exclamation-triangle"></i></div>
            <div v-else><i class="fa fa-4x fa-file-image-o"></i></div>
        </div>

        <input :id="'image__uploader_'+_uid" type="file" name="file" accept="image/*" class="image-uploader-file" ref="image" @change="uploadImage()" />
    </label>

    <modal v-if="modalShown" @close="closeModal()">
        <template slot="modal__header">Edit Image</template>

        <template slot="modal__body">
            <div class="form-group has-float-label" :class="{ 'has-error': errors.email }">
                <label class="control-label">Title</label>
                <input type="text" v-model="image.title" @keydown.13.prevent="updateImage()" :class="{ 'is-invalid': errors.title }"
                    class="form-control" placeholder="Add caption to the image" autocomplete="off" required />
                <span v-if="errors.title" class="invalid-feedback">{{ errors.title[0] }}</span>
            </div>
            <div class="form-group has-float-label" :class="{ 'has-error': errors.email }">
                <label class="control-label">Description</label>
                <textarea v-model="image.description" @keydown.13.prevent :class="{ 'is-invalid': errors.description }"
                    class="form-control noresize" rows="4" placeholder="Add description to the image"></textarea>
                <span v-if="errors.description" class="invalid-feedback">{{ errors.description[0] }}</span>
            </div>
        </template>

        <template slot="modal__footer">
            <button type="button" class="btn btn-outline-secondary mr-2" @click="closeModal()">Cancel</button>
            <button type="button" class="btn btn-outline-success" @click="updateImage()">Apply</button>
        </template>
    </modal>
</div>
</template>

<script>
import Modal from './Modal';

export default {
    components: {
        'modal': Modal,
    },

    props: {
        uploaded: {
            type: Object,
            required: false,
            default: () => this.nullableImage()
        },
        base_url: {
            String,
            required: true
        },
        input_name: {
            String,
            required: false,
            default: 'image_id'
        },
        input_value: {
            Number,
            required: false,
            default: 0
        },
        attachment_id: {
            Number,
            required: false,
            default: 0
        },
        attachment_type: {
            String,
            required: false,
            default: null
        },
    },

    data() {
        return {
            image: {},
            state: null,
            modalShown: false,
            errors: [],
            url: {
                upload: this.$props.base_url + '/upload',
                update: null,
                fetch: null,
                delete: null
            }
        }
    },

    watch: {
        'image.id'(id) {
            if (id > 0) {
                this.state = 'uploaded'
                this.$refs.image.value = '';
                this.url = Object.assign({}, this.url, {
                    update: this.$props.base_url + '/' + id,
                    fetch: this.$props.base_url + '/' + id,
                    delete: this.$props.base_url + '/' + id
                })
            } else if (id === 0) {
                this.state = null
            }
        },
    },

    created() {
        this.image = this.$props.uploaded

        if (this.$props.input_value > 0) {
            this.fetchImage(this.$props.base_url + '/' + this.$props.input_value)
        }
    },

    methods: {
        /**
         * Take first file from input field.
         *
         * @return {FormData}
         */
        takeFirstFile() {
            let files = this.$refs.image.files

            if (!files.length) {
                throw new Error(
                    'The file is not selected.'
                )
            }

            if (!files[0].type.match('image.*')) {
                throw new Error(
                    'The selected file is not an image.'
                )
            }

            let formData = new FormData()
            formData.append('file', files[0])

            if (this.$props.attachment_id > 0) {
                // formData.append('attachment_id', this.$props.attachment_id)
            }

            if (this.$props.attachment_type.length > 0) {
                // formData.append('attachment_type', this.$props.attachment_type)
            }

            return formData
        },

        /**
         * Upload image to server.
         *
         * @return {Promise}
         */
        async uploadImage() {
            try {
                this.state = 'uploading'

                const response = await axios({
                    method: 'post',
                    url: this.url.upload,
                    data: this.takeFirstFile(),
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (!response.data.file) {
                    throw new Error(response.data.message);
                }

                this.image = response.data.file
            } catch (error) {
                this.resetDataWithError(error.message)
            }
        },

        /**
         * Fetch image from server by id.
         *
         * @param  string  url
         * @return {Promise}
         */
        async fetchImage(url) {
            try {
                this.state = 'uploading';

                const response = await axios(url)

                if (!response.data.file) {
                    throw new Error(response.data.message)
                }

                this.image = response.data.file
            } catch (error) {
                this.resetDataWithError(error.message)
            }
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
        async updateImage() {
            try {
                this.state = 'uploading'

                const response = await axios.put(this.url.update, {
                    title: this.image.title,
                    description: this.image.description,
                });

                if (!response.data.file) {
                    throw new Error(response.data.message);
                }

                this.image = response.data.file
                Notification.success({
                    message: response.data.message
                })
                this.errors = []
                this.closeModal()
            } catch (error) {
                if (error.response && 422 === error.response.status) {
                    this.errors = error.response.data.errors
                } else {
                    console.log('Error', error.message)
                }
            }

            this.state = 'uploaded'
        },

        deleteImage() {
            if(! confirm('Delete this image from server?')) {
                return false
            }

            axios
                .delete(this.url.delete)
                .then((response) => {
                    this.resetData()
                    Notification.success({
                        message: response.data.message
                    })
                })
                .catch((error) => {
                    Notification.error({
                        message: error.message
                    })
                })
        },

        closeModal() {
            this.modalShown = false
        },

        /**
         * Resets the image data.
         */
        resetData() {
            this.image = this.nullableImage()
        },

        /**
         * Resets the image data and show error message.
         *
         * @param {string} message
         */
        resetDataWithError(message) {
            this.resetData()
            this.state = 'error'
            if (message) {
                Notification.error({
                    message: message
                })
            }
        },

        /**
         * Default data of image.
         *
         * @return {object}
         */
        nullableImage() {
            return {
                id: 0,
                url: null,
                title: null,
                description: null
            }
        },
    }
}
</script>

<style scoped>
label.image-uploader {
    cursor: pointer;
    display: block;
    margin-bottom: 1.25rem;
}

.image-uploader-variant {
    color: #e1e1e1;
    min-height: 163px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px dashed;
}

.image-uploader-success {
    /*color: #43ac6a;
    overflow: hidden;
    box-shadow: 0 8px 12px 0 rgba(0, 0, 0, .25);
    padding: .25rem;
    border: 1px solid rgba(0, 0, 0, .125);
    display: inline-block;*/
    color: #43ac6a;
    overflow: hidden;
    /* box-shadow: 0 8px 12px 0 rgba(0, 0, 0, .25); */
    /* padding: .25rem; */
    /* border: 1px solid rgba(0, 0, 0, .125); */
    display: inline-block;
    position: relative;
}

input.image-uploader-file {
    display: none;
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
}

.image-uploader .btn-toolbar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between!important;
    padding-top: 0.25rem;
    position: absolute;
    top: 0;
    width: 100%;
    padding: .25rem;
}
</style>
