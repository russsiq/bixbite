<template>
    <div class="container mt-5">
        <div v-if="category && category.id" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ category.attributes.title }}]</h6>
            <div class="card-body"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "categories-edit",

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
            category: null,
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
            this.category = entity;
        },
    },
};
</script>
