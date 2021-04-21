<template>
    <div class="container my-5">
        <div v-if="article" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ article.attributes.title }}]</h6>
            <div class="card-body">
                <article-form :article="article" @create="create" />
            </div>
        </div>
    </div>
</template>

<script>
import ArticleForm from "@/views/articles/form";

export default {
    name: "article-create",

    components: {
        "article-form": ArticleForm,
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            article: {
                attributes: {},
                relationships: {
                    categories: {
                        data: {}
                    }
                },
            },
        };
    },

    methods: {
        create({ attributes, relationships }) {
            this.$props.model
                .$create({
                    data: {
                        ...attributes,
                        relationships,
                    },
                });
        },
    },
};
</script>
