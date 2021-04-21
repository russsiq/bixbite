<template>
    <div class="container my-5">
        <div v-if="user && user.id" class="card">
            <h6 class="card-header">{{ $route.meta.title }} [{{ user.attributes.title }}]</h6>
            <div class="card-body">
                <user-form :user="user" @update="update" />
            </div>
        </div>
    </div>
</template>

<script>
import UserForm from "@/views/users/form";

export default {
    name: "user-edit",

    components: {
        "user-form": UserForm,
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
            user: {
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
        fillForm(user) {
            this.user = Object.assign({}, this.user, {
                ...user,
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
