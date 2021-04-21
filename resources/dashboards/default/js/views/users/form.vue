<template>
    <form
        action
        method="post"
        @submit.prevent="save"
        @keydown.ctrl.83.prevent="save"
        class="container"
    >
        <div class="row mb-3">
            <label for="name" class="col-lg-2 col-form-label">Name</label>
            <div class="col-lg-10">
                <input id="name" type="text" v-model="attributes.name" class="form-control" />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-outline-success">Save</button>
            </div>
        </div>

        <!-- <pre>{{ attributes }}</pre> -->
    </form>
</template>

<script>
import InputDatetimeLocal from "@/views/components/input-datetime-local";

export default {
    name: "user-form",

    components: {
        "input-datetime-local": InputDatetimeLocal,
    },

    props: {
        user: {
            type: Object,
            required: true,
        },
    },

    computed: {
        attributes() {
            return this.$props.user.attributes;
        },
    },

    methods: {
        save() {
            const action = this.$props.user.id ? "update" : "create";

            return this.$emit(action, {
                attributes: this.attributes,
            });
        },
    },
};
</script>

<style>
</style>
