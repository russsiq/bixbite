<template>
    <div class="container mt-5">
        <div v-if="comment && comment.id" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ comment.id }}]</h6>
            <div class="card-body"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "comments-edit",

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
            comment: null,
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
            this.comment = entity;
        },
    },
};
</script>
