<template>
    <div class="container my-5">
        <div v-if="category && category.id" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ category.attributes.title }}]</h6>
            <div class="card-body">
                <category-form :category="category" @update="update" />
            </div>
        </div>
    </div>
</template>

<script>
import CategoryForm from "@/views/categories/form";

export default {
    name: "category-edit",

    components: {
        "category-form": CategoryForm,
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
            category: {
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
        fillForm(category) {
            this.category = Object.assign({}, this.category, {
                ...category,
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
