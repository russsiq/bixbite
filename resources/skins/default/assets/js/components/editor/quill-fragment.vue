<template>
<modal v-if="promptContent" :size="'large'" @close="closeModal">
    <template slot="modal__header">
        <span v-if="isEditMode">Изменение фрагмента HTML-кода</span>
        <span v-else>Вставка фрагмента HTML-кода</span>
    </template>

    <template slot="modal__body">
        <form id="form_html_content" action="" method="post" @submit.prevent="embedHtmlFragment">
            <div class="has-float-label">
                <label class="control-label">Содержимое фрагмента</label>
                <textarea v-model="content" rows="8" class="form-control" required></textarea>
            </div>
        </form>
    </template>

    <template slot="modal__footer">
        <button v-if="isEditMode" type="button" form="form_html_content" class="btn btn-outline-danger mr-auto" @click="remove">Удалить</button>

        <button type="submit" form="form_html_content" class="btn btn-outline-success mr-2">
            <span v-if="isEditMode">Обновить</span>
            <span v-else>Вставить</span>
        </button>

        <button type="button" class="btn btn-outline-secondary" @click="closeModal">Отменить</button>
    </template>
</modal>
</template>

<script type="text/ecmascript-6">
import Quill from 'quill';
import Parchment from 'parchment';
import Modal from 'bxb-modal';

import FragmentBlot from './blots/FragmentBlot.js';
import fragmentHandler from './handlers/fragmentHandler';

export default {
    name: 'quill-fragment',

    components: {
        'modal': Modal,
    },

    props: {
        promptContent: {
            type: Boolean,
            required: true
        },

        blot: {
            type: FragmentBlot,
            required: false
        }
    },

    data() {
        return {
            content: '',
        }
    },

    computed: {
        isEditMode() {
            return this.blot instanceof FragmentBlot;
        }
    },

    watch: {
        blot(blot, oldContent) {
            this.content = blot instanceof FragmentBlot ? FragmentBlot.value(blot.domNode).content : '';
        }
    },

    methods: {
        embedHtmlFragment(event) {
            this.$emit('embed', {
                blot: this.blot,
                parameters: {
                    content: this.content
                }
            });
        },

        remove() {
            this.$emit('remove', this.blot);
        },

        closeModal() {
            this.$emit('close')
        },

    }
}
</script>

<style lang="scss">
.ql-fragment {
    border: 1px dashed #3bceff;
}

.ql-fragment:hover {
    border-style: solid;
}
</style>
