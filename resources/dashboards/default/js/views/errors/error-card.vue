<template>
    <div class="card text-center mt-5">
        <h2 class="my-3">{{ title }}</h2>
        <p v-if="description">{{ description }}</p>
    </div>
</template>

<script>
export default {
    name: "error-card",

    computed: {
        title() {
            return this.$route.meta.title;
        },

        description() {
            return this.$route.meta.description;
        },
    },
};
</script>
