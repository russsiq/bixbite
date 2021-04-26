<template>
<modal v-if="promptContent" :size="'large'" @close="closeModal">
    <template slot="modal__header">
        <span v-if="isEditMode">Изменение фрагмента HTML-кода</span>
        <span v-else>Вставка фрагмента HTML-кода</span>
    </template>

    <template slot="modal__body">
        <form id="form_html_content" action="" method="post" @submit.prevent="embedFragment">
            <div class="has-float-label">
                <label class="control-label">Содержимое фрагмента</label>
                <textarea v-model="content" rows="8" class="form-control" required></textarea>
            </div>
        </form>
    </template>

    <template slot="modal__footer">
        <button v-if="isEditMode" type="button" form="form_html_content" class="btn btn-outline-danger mr-auto" @click="removeFragment">Удалить</button>

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
        quill: {
            type: Quill,
        },

        blot: {
            type: FragmentBlot,
        },

        promptContent: {
            type: Boolean,
            required: true,
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
        },
    },

    watch: {
        blot(blot, oldContent) {
            this.content = blot instanceof FragmentBlot ? FragmentBlot.value(blot.domNode).content : '';
        },
    },

    methods: {
        /**
         * Встроить фрагмент HTML-кода.
         */
        embedFragment(event) {
            fragmentHandler(this.quill, ({
                blot: this.blot,
                parameters: {
                    content: this.content
                }
            }));

            this.closeModal();
        },

        /**
         * Удалить фрагмент из текста.
         */
        removeFragment(blot) {
            this.blot.remove && this.blot.remove();

            this.closeModal();
        },

        closeModal() {
            this.$emit('close', {
                blot: null,
                promptContent: false
            });
        },
    }
}
</script>

<style lang="scss">
.ql-fragment:not(button) {
    border: 1px dashed #3bceff;
    margin-bottom: 1rem;
    &:hover {
        border-style: solid;
    }
}
</style>
