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
            <label for="teaser" class="col-lg-2 col-form-label">Teaser</label>
            <div class="col-lg-10">
                <textarea id="teaser" v-model="attributes.teaser" class="form-control" rows="2"></textarea>
            </div>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea id="content" v-model="attributes.content" class="form-control" rows="10"></textarea>
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

        <div class="row mb-3">
            <label for="views" class="col-lg-2 col-form-label">Views</label>
            <div class="col-lg-10">
                <input id="views" type="number" v-model="attributes.views" class="form-control" />
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

        <div v-if="attributes.user_id" class="row mb-3">
            <label for="user_id" class="col-sm-2 col-form-label">User id</label>
            <div class="col-sm-10">
                <input
                    id="user_id"
                    type="number"
                    v-model="attributes.user_id"
                    class="form-control-plaintext"
                    readonly
                />
            </div>
        </div>

        <div v-if="article.id" class="row mb-3">
            <label for="categories" class="col-sm-2 col-form-label">Categories</label>
            <div class="col-sm-10">
                <categories-items
                    :categoryable="{id: article.id, type: article.type}"
                    :value="relationships.categories.data || []"
                    @update:categories="syncRelations('categories', $event)"
                ></categories-items>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 offset-lg-2">
                <div class="form-check mb-3">
                    <input
                        id="on_mainpage"
                        class="form-check-input"
                        type="checkbox"
                        v-model="attributes.on_mainpage"
                    />
                    <label for="on_mainpage" class="form-check-label">On mainpage</label>
                </div>

                <div class="form-check mb-3">
                    <input
                        id="is_favorite"
                        class="form-check-input"
                        type="checkbox"
                        v-model="attributes.is_favorite"
                    />
                    <label for="is_favorite" class="form-check-label">Is favorite</label>
                </div>

                <div class="form-check mb-3">
                    <input
                        id="is_pinned"
                        class="form-check-input"
                        type="checkbox"
                        v-model="attributes.is_pinned"
                    />
                    <label for="is_pinned" class="form-check-label">Is pinned</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-outline-success">Save</button>
            </div>
        </div>

        <pre>{{ attributes }}</pre>
        <pre>{{ relationships }}</pre>
    </form>
</template>

<script>
import CategoriesItems from "./partials/categories-items";
import InputDatetimeLocal from "@/views/components/input-datetime-local";
import TagsItems from "./partials/tags-items";

export default {
    name: "article-form",

    components: {
        "categories-items": CategoriesItems,
        "input-datetime-local": InputDatetimeLocal,
        "tags-items": TagsItems,
    },

    props: {
        article: {
            type: Object,
            required: true,
        },
    },

    computed: {
        attributes() {
            return this.$props.article.attributes;
        },
        relationships() {
            return this.$props.article.relationships;
        },
    },

    methods: {
        syncRelations(relationship, values) {
            this.relationships[relationship]["data"] = values.map(
                (relation) => {
                    return {
                        id: relation.id,
                    };
                }
            );

            this.$props.article.id && this.save();
        },

        update(attribute, value) {
            this.attributes[attribute] = value;

            return this;
        },

        save() {
            const action = this.$props.article.id ? "update" : "create";

            return this.$emit(action, {
                attributes: this.attributes,
                relationships: this.relationships,
            });
        },
    },
};
</script>

<style>
</style>
