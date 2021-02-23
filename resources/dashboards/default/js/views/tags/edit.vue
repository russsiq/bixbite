<template>
    <div class="container mt-5">
        <div v-if="tag && tag.id" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ tag.attributes.title }}]</h6>
            <div class="card-body"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "tags-edit",

    props: {
        model: {
            type: Function,
            required: true,
        },

        id: {
            type: Number,
            required: true,
        },
    },

    data() {
        return {
            tag: null,
        };
    },

    mounted() {
        this.$props.model
            .$get({
                params: {
                    id: this.$props.id,
                },
            })
            .then(this.fillForm);
    },

    methods: {
        fillForm(entity) {
            this.tag = entity;
        },
    },
};
</script>
