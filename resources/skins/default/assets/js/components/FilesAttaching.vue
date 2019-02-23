<template>
<div class="table-responsive">
    <table class="table table-sm mb-0" v-if="files.length > 0">
        <tbody>
            <tr v-for="(file, key) in files" :key="key">
                <td class="baguetteBox text-center">
                    <div class="card-file-icon" v-if="'wait' == file.state"><i class="fa fa-spinner fa-pulse text-primary"></i></div>
                    <div class="card-file-icon" v-else-if="'error' == file.state"><i class="fa fa-ban text-danger"></i></div>
                    <div v-else>
                        <a :href="file.url" target="_blank" v-if="'image'== file.type">
                            <img :src="file.url" :alt="file.title" :title="file.title" class="card-file-icon" width="42" />
                        </a>
                        <a href="#" class="media-link" v-else-if="'audio'== file.type" @click.prevent="mediaModal(file)">
                            <div class="card-file-icon"><i class="fa fa-music"></i></div>
                        </a>
                        <a href="#" class="media-link" v-else-if="'video'== file.type" @click.prevent="mediaModal(file)">
                            <div class="card-file-icon"><i class="fa fa-film"></i></div>
                        </a>
                        <div class="card-file-icon" v-else-if="'archive'== file.type"><i class="fa fa-archive"></i></div>
                        <div class="card-file-icon" v-else><i class="fa fa-file"></i></div>
                    </div>
                </td>
                <td>
                    {{ file.title }}
                    <!--br>{{ file.url }}-->
                    <div v-html="file.message" class=""></div>
                </td>
                <td style="white-space: nowrap;">
                    <span v-if="file.id > 0">
                        <code>[[file_{{ file.id }}]]</code>
                        <code v-if="isImageFile(file)">[[picture_box_{{ file.id }}]]</code>
                        <code v-if="isMediaFile(file)">[[media_player_{{ file.id }}]]</code>
                        <br>
                        <code v-if="isMediaFile(file)">[[download_button_{{ file.id }}]]</code>
                    </span>
                </td>
                <td style="white-space: nowrap;" class="text-right">
                    <div class="btn-group ml-auto">
                        <div v-if="file.id > 0">
                            <button type="button" class="btn btn-link text-primary" @click="editFileModal(file, key)"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-link text-danger" @click="deleteFile(key)"><i class="fa fa-trash"></i></button>
                        </div>
                        <div v-else>
                            <button type="button" class="btn btn-link text-primary" @click="uploadFile(key)"><i class="fa fa-upload"></i></button>
                            <button type="button" class="btn btn-link text-warning" @click="removeFile(key)"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <hr class="m-0" />

    <div class="card-body">
        <input type="file" ref="files" @change="handleFiles()" multiple />
    </div>

    <media-modal v-if="mediaModalShown" :media="media" @close="closeMediaModal()"></media-modal>
    <edit-file-modal v-if="editFileModalShown" :file="file" @updated="updateFile" @close="closeEditFileModal()"></edit-file-modal>
</div>
</template>

<script type="text/ecmascript-6">

import baguetteBox from 'baguettebox.js'
import MediaModal from './MediaModal'
import EditFileModal from './editFileModal'

export default {
    components: {
        'media-modal': MediaModal,
        'edit-file-modal': EditFileModal,
    },

    props: {
        file_url: {
            String,
            // required: true
        },
        attachment_id: {
            Number,
            required: true
        },
        attachment_type: {
            String,
            required: true
        },
    },

    data() {
        return {
            files: [],
            media: null,

            /**
             * Current file to edit in modal.
             * @type {object}
             */
            file: null,

            mediaModalShown: false,
            editFileModalShown: false,
        }
    },

    created() {
        this.fetchFiles()
        this.lang.loadFromJsonPath('files')
    },

    mounted() {
        // Executed after the next DOM update cycle.
        this.$nextTick(() => {
            setTimeout(() => {
                baguetteBox.run('.baguetteBox', {
                    // bug with sticky bar and document.documentElement.style.overflowY = 'auto';
                    // noScrollbars: true,
                    captions(element) {
                        return element.getElementsByTagName('img')[0].title;
                    }
                })
            }, 100);
        });
    },

    methods: {
        handleFiles() {
            let uploadFiles = this.$refs.files.files;

            // @need items = [item:{file,id,key,url,message,state,type}]
            Array.from(uploadFiles, (file, index) => {
                this.files.push({
                    file,
                    id: 0,
                    title: file.name,
                    key: index + this.files.length,
                    url: null,
                    message: null,
                    state: false,
                    type: 'other', // Not assign!,
                })
            });

            this.submitFiles()
        },

        submitFiles() {
            // Check how many elements in ref="files".
            if (!this.$refs.files.files.length) {
                return alert('Nothing to upload')
            }

            for (let i = 0; i < this.files.length; i++) {
                if (this.files[i].id == 0) {
                    this.uploadFile(i)
                }
            }

            this.$refs.files.value = ''
        },

        removeFile(key) {
            this.files.splice(key, 1)
        },

        /**
         * Fetch files from server by attachment_id and attachment_type.
         *
         * @param  string  url
         * @return {Promise}
         */
        async fetchFiles() {
            try {
                const response = await axios.get(this.$props.file_url, {
                    params: {
                        attachment_id: this.$props.attachment_id,
                        attachment_type: this.$props.attachment_type,
                    }
                })

                if (!response.data.files) {
                    throw new Error(response.data.message)
                }

                this.files = response.data.files
            } catch (error) {
                console.log(error)
            }
        },

        async uploadFile(key) {
            if (!this.$props.attachment_id || !this.$props.attachment_type) {
                Notification.warning({
                    message: 'Before you can upload files, you must save the article.'
                })

                return false
            }

            let formData = new FormData();
            formData.append('file', this.files[key].file);
            formData.append('attachment_id', this.$props.attachment_id)
            formData.append('attachment_type', this.$props.attachment_type)

            this.files.splice(key, 1, Object.assign(this.files[key], {
                state: 'wait',
                message: null,
            }))

            try {
                const response = await axios({
                    method: 'post',
                    url: this.$props.file_url + '/upload',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (!response.data.file) {
                    throw new Error(response.data.message);
                }

                this.files.splice(key, 1, Object.assign(this.files[key], {
                    id: response.data.file.id,
                    title: response.data.file.title,
                    url: response.data.file.url,
                    state: 'uploaded',
                    message: response.data.message,
                    type: response.data.file.type,
                }))
            } catch (error) {
                this.files.splice(key, 1, Object.assign(this.files[key], {
                    state: 'error',
                    message: error.message,
                }))
            }
        },

        deleteFile(key) {
            if (!confirm(this.trans('msg.sure_del_file'))) {
                return false
            }

            this.files.splice(key, 1, Object.assign(this.files[key], {
                state: 'wait',
                message: null,
            }))

            axios
                .delete(this.$props.file_url + '/' + this.files[key].id)
                .then((response) => {
                    this.files.splice(key, 1)
                    Notification.success({
                        message: response.data.message
                    })
                })
                .catch((error) => {
                    this.files.splice(key, 1, Object.assign(this.files[key], {
                        state: 'error',
                        message: error.message,
                    }))
                })
        },

        isImageFile(file) {
            return 'image' === file.type
        },

        isMediaFile(file) {
            return ['audio', 'video'].indexOf(file.type) != -1
        },

        /**
         * Open the media modal to view media content.
         */
        mediaModal(file) {
            this.media = file
            this.mediaModalShown = true
        },

        /**
         * Close the media modal.
         */
        closeMediaModal() {
            this.media = null
            this.mediaModalShown = false
        },

        /**
         * Open the modal to edit file.
         */
        editFileModal(file, key) {
            this.file = Object.assign(file, {
                key
            })
            this.editFileModalShown = true
        },

        /**
         * Close the edit file modal.
         */
        closeEditFileModal() {
            this.file = null
            this.editFileModalShown = false
        },

        updateFile(attr) {
            let key = this.file.key
            this.files.splice(key, 1, Object.assign(this.files[key], {
                title: attr.title,
                description: attr.description,
            }))
        }
    }
}
</script>

<style lang="scss" scoped>
/**
 *
 */
</style>
