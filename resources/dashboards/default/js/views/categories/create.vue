<template>
    <div class="container my-5">
        <div v-if="category" class="card">
            <h6 class="card-header">{{ $route.meta.title }}</h6>
            <div class="card-body">
                <category-form :category="category" @create="create" />
            </div>
        </div>
    </div>
</template>

<script>
import CategoryForm from "@/views/categories/form";

export default {
    name: "category-create",

    components: {
        "category-form": CategoryForm,
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            category: {
                attributes: {
                    parent_id: 0,
                },
            },
        };
    },

    methods: {
        create() {
            this.$props.model.$create({
                data: {
                    ...this.category.attributes,
                },
            });
        },
    },
};
</script>
