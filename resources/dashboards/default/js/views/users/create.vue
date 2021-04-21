<template>
    <div class="container my-5">
        <div v-if="user" class="card">
            <h6 class="card-header">{{ $route.meta.title }}</h6>
            <div class="card-body">
                <user-form :user="user" @create="create" />
            </div>
        </div>
    </div>
</template>

<script>
import UserForm from "@/views/users/form";

export default {
    name: "user-create",

    components: {
        "user-form": UserForm,
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            user: {
                attributes: {},
            },
        };
    },

    methods: {
        create() {
            this.$props.model.$create({
                data: {
                    ...this.user.attributes,
                },
            });
        },
    },
};
</script>
