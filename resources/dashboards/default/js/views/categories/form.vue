<template>
    <form
        action
        method="post"
        @submit.prevent="save"
        @keydown.ctrl.83.prevent="save"
        class="container"
    >
        <div class="row mb-3">
            <label for="title" class="col-lg-2 col-form-label">Title</label>
            <div class="col-lg-10">
                <input id="title" type="text" v-model="attributes.title" class="form-control" />
            </div>
        </div>

        <div class="row mb-3">
            <label for="slug" class="col-lg-2 col-form-label">Slug</label>
            <div class="col-lg-10">
                <input id="slug" type="text" v-model="attributes.slug" class="form-control" />
            </div>
        </div>

        <div class="row mb-3">
            <label for="info" class="col-lg-2 col-form-label">Info</label>
            <div class="col-lg-10">
                <textarea id="info" v-model="attributes.info" class="form-control" rows="8"></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label for="meta_description" class="col-lg-2 col-form-label">Meta description</label>
            <div class="col-lg-10">
                <textarea
                    id="meta_description"
                    v-model="attributes.meta_description"
                    class="form-control"
                    rows="2"
                ></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label for="meta_keywords" class="col-lg-2 col-form-label">Meta keywords</label>
            <div class="col-lg-10">
                <input
                    id="meta_keywords"
                    type="text"
                    v-model="attributes.meta_keywords"
                    class="form-control"
                />
            </div>
        </div>

        <div class="row mb-3">
            <label for="meta_robots" class="col-lg-2 col-form-label">Meta robots</label>
            <div class="col-lg-10">
                <select
                    id="meta_robots"
                    v-model="attributes.meta_robots"
                    class="form-select"
                    aria-label="Select meta robots"
                >
                    <option value="all">all</option>
                    <option value="noindex">noindex</option>
                    <option value="nofollow">nofollow</option>
                    <option value="none">none</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 offset-lg-2">
                <div class="form-check mb-3">
                    <input
                        id="show_in_menu"
                        class="form-check-input"
                        type="checkbox"
                        v-model="attributes.show_in_menu"
                    />
                    <label for="show_in_menu" class="form-check-label">Show in menu</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="paginate" class="col-lg-2 col-form-label">Paginate</label>
            <div class="col-lg-10">
                <input
                    id="paginate"
                    type="number"
                    v-model="attributes.paginate"
                    class="form-control"
                />
            </div>
        </div>

        <div class="row mb-3">
            <label for="template" class="col-lg-2 col-form-label">Template</label>
            <div class="col-lg-10">
                <input id="template" type="text" v-model="attributes.template" class="form-control" />
            </div>
        </div>

        <div class="row mb-3">
            <label for="order_by" class="col-lg-2 col-form-label">Order by</label>
            <div class="col-lg-10">
                <select
                    id="order_by"
                    v-model="attributes.order_by"
                    class="form-select"
                    aria-label="Select Order by"
                >
                    <option value="desc">desc</option>
                    <option value="asc">asc</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="created_at" class="col-lg-2 col-form-label">Created at</label>
            <div class="col-lg-10">
                <input-datetime-local
                    id="created_at"
                    v-model="attributes.created_at"
                    class="form-control"
                />
            </div>
        </div>

        <div class="row mb-3">
            <label for="updated_at" class="col-lg-2 col-form-label">Updated at</label>
            <div class="col-lg-10">
                <input-datetime-local
                    id="updated_at"
                    v-model="attributes.updated_at"
                    class="form-control"
                />
            </div>
        </div>

        <div class="row mb-3">
            <label for="parent_id" class="col-lg-2 col-form-label">Parent</label>
            <div class="col-lg-10">
                <select
                    id="parent_id"
                    v-model="attributes.parent_id"
                    class="form-select"
                    aria-label="Select parent id"
                >
                    <option :value="0">-- none --</option>
                    <template v-for="category in categories">
                        <option
                            :key="category.id"
                            :value="category.id"
                        >{{ category.attributes.title }}</option>
                    </template>
                </select>
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
import Category from "@/store/models/category";
import InputDatetimeLocal from "@/views/components/input-datetime-local";

export default {
    name: "category-form",

    components: {
        "input-datetime-local": InputDatetimeLocal,
    },

    props: {
        category: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            categories: null,
        };
    },

    computed: {
        attributes() {
            return this.$props.category.attributes;
        },
    },

    mounted() {
        Category.$fetch().then((categories) => {
            this.categories = categories;
        });
    },

    methods: {
        save() {
            const action = this.$props.category.id ? "update" : "create";

            return this.$emit(action, {
                attributes: this.attributes,
            });
        },
    },
};
</script>

<style>
</style>
