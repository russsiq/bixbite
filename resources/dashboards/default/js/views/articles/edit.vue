<template>
    <div class="container mt-5">
        <div v-if="article && article.id" class="card">
            <h6 class="card-header">Edit article [{{ article.attributes.title }}]</h6>
            <div class="card-body"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "articles-edit",

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
            article: null,
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
        fillForm(article) {
            this.article = article;
        },
    },
};
</script>
