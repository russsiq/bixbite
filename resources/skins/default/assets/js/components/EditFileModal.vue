<template>
<modal @close="close()">
    <template slot="modal__header">{{ $props.file.title || lang('Html5 super mega modal player') }}</template>

    <template slot="modal__body">
        <div class="form-group has-float-label" :class="{ 'has-error': errors.title }">
            <label class="control-label">{{ lang('title') }}</label>
            <input type="text" v-model="title" @keydown.13.prevent="updateFile()" :class="{ 'is-invalid': errors.title }" class="form-control" placeholder="Add caption to the image" autocomplete="off" required />
            <span v-if="errors.title" class="invalid-feedback">{{ errors.title[0] }}</span>
        </div>
        <div class="form-group has-float-label" :class="{ 'has-error': errors.description }">
            <label class="control-label">{{ lang('description') }}</label>
            <textarea v-model="description" @keydown.13.prevent :class="{ 'is-invalid': errors.description }" class="form-control noresize" rows="4" placeholder="Add description to the image"></textarea>
            <span v-if="errors.description" class="invalid-feedback">{{ errors.description[0] }}</span>
        </div>
    </template>

    <template slot="modal__footer">
        <button type="button" class="btn btn-outline-secondary mr-2" @click="close()">{{ lang('btn.cancel') }}</button>
        <button type="button" class="btn btn-outline-success" @click="updateFile()">{{ lang('btn.save') }}</button>
    </template>
</modal>
</template>

<script type="text/ecmascript-6">

import axios from 'axios'
import Modal from 'bxb-modal'

export default {
    components: {
        'modal': Modal,
    },

    props: {
        // media.url, media.type, media.title
        file: {
            type: Object,
            required: true
        },
    },

    data() {
        return {
            errors: [],
            id: 0,
            title: null,
            description: null,
        }
    },

    mounted() {
        this.id = this.$props.file.id
        this.title = this.$props.file.title
        this.description = this.$props.file.description
    },

    methods: {

        close() {
            this.errors = []
            // if (this.errors.length == 0) {
                // if (confirm('Close this window?')) {
                    this.$emit('close')
                // }
            // }
        },

        /**
         * Add a new title and description to file.
         */
        async updateFile() {
            try {
                const response = await axios.put(
                    this.$parent.$props.file_url + '/' + this.id, {
                        title: this.title,
                        description: this.description,
                    });

                if (!response.data.file) {
                    throw new Error(response.data.message);
                }

                Notification.success({
                    message: response.data.message
                })

                this.$emit('updated', {
                    title: response.data.file.title,
                    description: response.data.file.description
                })
                this.close()
            } catch (error) {
                if (error.response && 422 === error.response.status) {
                    this.errors = error.response.data.errors
                } else {
                    console.log('Error', error.message)
                }
            }
        },
    }
}
</script>

<style lang="scss" scoped>

</style>
