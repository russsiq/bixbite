<template>
<div class="row">
    Themes
</div>
</template>

<script type="text/ecmascript-6">
export default {
    name: 'themes-index',

    data() {
        return {}
    },

    computed: {},

    created() {},

    methods: {}
}
</script>

<style lang="scss" scoped>
.theme-card {
    background-size: cover;
    box-shadow: 0 8px 12px 0 rgba(0, 0, 0, 0.25);
    font-size: 0.78rem;
    color: #ddd;
    height: 100%;
}

.theme-card .color-overlay {
    height: 100%;
    background: linear-gradient(to right, rgba(42, 159, 255, 0.2) 0%, #212120 60%, #212120 100%);
    background-blend-mode: multiply;
}

.theme-card .theme-content {
    width: 40%;
    float: right;
    padding-right: 1.25rem;
    height: 100%;
    margin-bottom: 0;
}

.theme-card .theme-content .theme-title {
    color: #ffffff;
    text-transform: uppercase;
    line-height: 1;
    letter-spacing: 4px;
    margin: 0.75rem 0;
}

.theme-card .theme-content .theme-info {
    letter-spacing: 1px;
    font-size: 0.8rem;
    line-height: 1;
    margin: 0;
    opacity: 0.6;
}

.theme-card .theme-content .theme-header {
    margin-bottom: 3rem;
}

.theme-card .theme-content .theme-desc {
    margin-bottom: 4.5rem;
}

.theme-card .theme-content .theme-btn {
    bottom: 1.25rem;
    right: 2.25rem;
    position: absolute;
}

.theme-card .icon-block {
    padding: 1.25em;
    display: inline-block;
}

.theme-card .icon-block__icon i {
    margin-right: 0.5em;
    color: #ffffff;
    opacity: 0.8;
}

.theme-card .icon-block__icon i:hover {
    opacity: 1;
}

@media (max-width: 992px) {
    .theme-card .theme-content {
        width: 100%;
        float: none;
        padding: 1.25rem;
    }

    .theme-card .color-overlay {
        background: linear-gradient(to bottom, rgba(0, 140, 186, 0.2) 0%, #212120 60%, #212120 100%);
    }

    .theme-card .icon-block {
        display: block;
        width: 100%;
    }
}
</style>
