<template>
<div class="table-responsive">
    <table class="table table-sm mb-0" v-if="attachments.length > 0">
        <tbody>
            <tr v-for="(attachment, key) in attachments" :key="key">
                <td class="baguetteBox text-center">
                    <div class="card-file-icon" v-if="'wait' == attachment.state"><i class="fa fa-spinner fa-pulse text-primary"></i></div>
                    <div class="card-file-icon" v-else-if="'error' == attachment.state"><i class="fa fa-ban text-danger"></i></div>
                    <div v-else>
                        <a :href="attachment.url" target="_blank" v-if="'image'== attachment.type">
                            <img :src="attachment.url" :alt="attachment.title" :title="attachment.title" class="card-file-icon" width="42" />
                        </a>
                        <a href="#" class="media-link" v-else-if="'audio'== attachment.type" @click.prevent="mediaModal(attachment)">
                            <div class="card-file-icon"><i class="fa fa-music"></i></div>
                        </a>
                        <a href="#" class="media-link" v-else-if="'video'== attachment.type" @click.prevent="mediaModal(attachment)">
                            <div class="card-file-icon"><i class="fa fa-film"></i></div>
                        </a>
                        <div class="card-file-icon" v-else-if="'archive'== attachment.type"><i class="fa fa-archive"></i></div>
                        <div class="card-file-icon" v-else><i class="fa fa-file"></i></div>
                    </div>
                </td>
                <td>
                    {{ attachment.title }}
                    <!--br>{{ attachment.url }}-->
                    <div v-html="attachment.message" class=""></div>
                </td>
                <td style="white-space: nowrap;">
                    <span v-if="attachment.id > 0">
                        <code>[[attachment_{{ attachment.id }}]]</code>
                        <code v-if="isImageFile(attachment)">[[picture_box_{{ attachment.id }}]]</code>
                        <code v-if="isMediaFile(attachment)">[[media_player_{{ attachment.id }}]]</code>
                        <br>
                        <code v-if="isMediaFile(attachment)">[[download_button_{{ attachment.id }}]]</code>
                    </span>
                </td>
                <td style="white-space: nowrap;" class="text-right">
                    <div class="btn-group ms-auto">
                        <div v-if="attachment.id > 0">
                            <button type="button" class="btn btn-link text-primary" @click="editFileModal(attachment, key)"><i class="fa fa-pencil"></i></button>
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
        attachable_id: {
            Number,
            required: true
        },
        attachable_type: {
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
    },

    async mounted() {
        await this.loadFromJsonPath('attachments')

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
                this.attachments.push({
                    file,
                    id: 0,
                    title: file.name,
                    key: index + this.attachments.length,
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

            for (let i = 0; i < this.attachments.length; i++) {
                if (this.attachments[i].id == 0) {
                    this.uploadFile(i)
                }
            }

            this.$refs.files.value = ''
        },

        removeFile(key) {
            this.attachments.splice(key, 1)
        },

        /**
         * Fetch files from server by attachable_id and attachable_type.
         *
         * @param  string  url
         * @return {Promise}
         */
        async fetchFiles() {
            try {
                const response = await axios.get(this.$props.file_url, {
                    params: {
                        attachable_id: this.$props.attachable_id,
                        attachable_type: this.$props.attachable_type,
                    }
                })

                if (!response.data.attachments) {
                    throw new Error(response.data.message)
                }

                this.attachments = response.data.attachments
            } catch (error) {
                console.log(error)
            }
        },

        async uploadFile(key) {
            if (!this.$props.attachable_id || !this.$props.attachable_type) {
                this.$notification.warning({
                    message: this.lang.trans('Before you can upload attachments, you must save the article.')
                })

                return false
            }

            let formData = new FormData();
            formData.append('uploaded_file', this.attachments[key].file);
            formData.append('attachable_id', this.$props.attachable_id)
            formData.append('attachable_type', this.$props.attachable_type)

            this.attachments.splice(key, 1, Object.assign(this.attachments[key], {
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

                this.attachments.splice(key, 1, Object.assign(this.attachments[key], {
                    id: response.data.file.id,
                    title: response.data.file.title,
                    url: response.data.file.url,
                    state: 'uploaded',
                    message: response.data.message,
                    type: response.data.file.type,
                }))
            } catch (error) {
                this.attachments.splice(key, 1, Object.assign(this.attachments[key], {
                    state: 'error',
                    message: error.message,
                }))
            }
        },

        deleteFile(key) {
            if (!confirm(__('msg.sure_del_file'))) {
                return false
            }

            this.attachments.splice(key, 1, Object.assign(this.attachments[key], {
                state: 'wait',
                message: null,
            }))

            axios
                .delete(this.$props.file_url + '/' + this.attachments[key].id)
                .then((response) => {
                    this.attachments.splice(key, 1)
                    this.$notification.success({
                        message: response.data.message
                    })
                })
                .catch((error) => {
                    this.attachments.splice(key, 1, Object.assign(this.attachments[key], {
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
            this.attachments.splice(key, 1, Object.assign(this.attachments[key], {
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
