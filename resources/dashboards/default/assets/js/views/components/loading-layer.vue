<template>
<div class="loading_layer" v-if="isActive">
    <div class="spinner">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <path stroke="none" d="M12 50A38 38 0 0 0 88 50A38 41 0 0 1 12 50" fill="#008cba" transform="rotate(322.127 50 51.5)">
                <animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 51.5;360 50 51.5" keyTimes="0;1" dur="0.8s" begin="0s" repeatCount="indefinite"></animateTransform>
            </path>
        </svg>
    </div>
</div>
</template>

<script type="text/ecmascript-6">
import {
    mapGetters
} from 'vuex';

export default {
    name: 'loading-layer',

    computed: {
        ...mapGetters({
            'isActive': 'loadingLayer/show'
        }),
    },
}
</script>

<style lang="scss" scoped>
.loading_layer {
    overflow: hidden;
    z-index: 998;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: rgba(255, 255, 255, .4);
}

.spinner {
    z-index: 999;
    position: absolute;
    width: 5em;
    height: 5em;
    top: calc(50% - 2.5em);
    left: calc(50% - 2.5em);
    background: none;
}
</style>
