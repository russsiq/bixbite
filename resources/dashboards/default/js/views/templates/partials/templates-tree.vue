<template>
<li class="tree_view__item">
    <template v-if="isDir">
        <a :href="`#collapse-${item.id}`" class="tree_view__link" data-toggle="collapse">
            <i class="fa fa-folder-o text-muted"></i> {{ item.name }}
        </a>

        <ul :id="`collapse-${item.id}`" class="tree_view__subitem collapse">
            <templates-tree v-for="(child, j) in item.children" :item="child" :key="child.id" />
        </ul>
    </template>

    <template v-else>
        <a href="#" class="tree_view__link" :data-path="item.filename">
            <i class="fa fa-file-text-o text-muted"></i> {{ item.name }}
        </a>
    </template>
</li>
</template>

<script type="text/ecmascript-6">
export default {
    name: 'templates-tree',

    props: {
        item: {
            type: Object,
            required: true
        },
    },

    data() {
        return {}
    },

    computed: {
        /**
         * Текущий элемент является директорией.
         * @return {Boolean} [description]
         */
        isDir() {
            return this.$props.item.children.length > 0;
        },

        /**
         * В данный момент не используется.
         * Название для шаблона.
         * @return {String} [description]
         */
        title() {
            return this.trans('#' + this.$props.item.name);
        },
    },
}
</script>
