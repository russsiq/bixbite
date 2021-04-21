<template>
    <div class="container my-5">
        <div v-if="comment && comment.id" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ comment.attributes.title }}]</h6>
            <div class="card-body">
                <comment-form :comment="comment" @update="update" />
            </div>
        </div>
    </div>
</template>

<script>
import CommentForm from "@/views/comments/form";

export default {
    name: "comment-edit",

    components: {
        "comment-form": CommentForm,
    },

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
            comment: {
                attributes: {},
            },
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
        fillForm(comment) {
            this.comment = Object.assign({}, this.comment, {
                ...comment,
            });
        },

        update({ attributes }) {
            this.$props.model
                .$update({
                    params: {
                        id: this.$props.id,
                    },
                    data: {
                        ...attributes,
                    },
                })
                .then(this.fillForm);
        },
    },
};
</script>
