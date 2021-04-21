<template>
    <div class="container my-5">
        <div v-if="comment" class="card">
            <h6 class="card-header">{{ $route.meta.title }}</h6>
            <div class="card-body">
                <comment-form :comment="comment" @create="create" />
            </div>
        </div>
    </div>
</template>

<script>
import CommentForm from "@/views/comments/form";

export default {
    name: "comment-create",

    components: {
        "comment-form": CommentForm,
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            comment: {
                attributes: {},
            },
        };
    },

    methods: {
        create() {
            this.$props.model.$create({
                data: {
                    ...this.comment.attributes,
                },
            });
        },
    },
};
</script>
