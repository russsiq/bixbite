<template>
    <div class="container mt-5">
        <div v-if="user && user.id" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ user.attributes.name }}]</h6>
            <div class="card-body"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "users-edit",

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
            user: null,
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
            this.user = entity;
        },
    },
};
</script>
