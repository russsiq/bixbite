<template>
    <div class="container my-5">
        <div v-if="tag" class="card">
            <h6 class="card-header">{{ $route.meta.title }}</h6>
            <div class="card-body">
                <tag-form :tag="tag" @create="create" />
            </div>
        </div>
    </div>
</template>

<script>
import TagForm from "@/views/tags/form";

export default {
    name: "tag-create",

    components: {
        "tag-form": TagForm,
    },

    props: {
        model: {
            type: Function,
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

    methods: {
        create() {
            this.$props.model.$create({
                data: {
                    ...this.tag.attributes,
                },
            });
        },
    },
};
</script>
