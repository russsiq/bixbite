<template>
    <div class="container my-5">
        <div v-if="tag && tag.id" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ tag.attributes.title }}]</h6>
            <div class="card-body">
                <tag-form :tag="tag" @update="update" />
            </div>
        </div>
    </div>
</template>

<script>
import TagForm from "@/views/tags/form";

export default {
    name: "tag-edit",

    components: {
        "tag-form": TagForm,
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
            tag: {
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
        fillForm(tag) {
            this.tag = Object.assign({}, this.tag, {
                ...tag,
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
