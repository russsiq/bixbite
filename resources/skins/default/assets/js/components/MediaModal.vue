<template>
<modal @close="close()">
    <template slot="modal__header">{{ $props.media.title || 'Html5 super mega modal player' }}</template>

    <template slot="modal__body">
        <video v-if="'video'== $props.media.type" preload="none" :src="$props.media.url" controls></video>
        <audio v-else-if="'audio'== $props.media.type" preload="none" :src="$props.media.url" controls></audio>
    </template>

    <template slot="modal__footer">
        <button type="button" class="btn btn-outline-success" @click="close()">Close</button>
    </template>
</modal>
</template>

<script type="text/ecmascript-6">
import Modal from 'bxb-modal'

export default {
    components: {
        'modal': Modal,
    },

    props: {
        // media.url, media.type, media.title
        media: {
            type: Object,
            required: true
        },
    },

    data() {
        return {
            //
        }
    },

    mounted() {
        //
    },

    methods: {
        close() {
            // if (confirm('Close this window?')) {
                this.$emit('close')
            // }
        },
    }
}
</script>

<style lang="scss" scoped>
audio,
video {
    object-fit: scale-down;
    width: 100%;
}
</style>
