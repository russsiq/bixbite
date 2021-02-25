<template>
    <filterable v-bind="filterable" :collection.sync="collection">
        <template #title>Articles</template>

        <template #first-group></template>

        <template #second-group>
            <button type="button" class="btn btn-outline-success">Create</button>
        </template>

        <template #thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Categories</th>
                <th scope="col">Tags</th>
                <th scope="col">User</th>
                <th scope="col" class="text-end">Actions</th>
            </tr>
        </template>

        <template #trow="{row}">
            <tr :key="row.id">
                <th scope="row">{{ row.id }}</th>
                <td>{{ row.attributes.title }}</td>
                <td>
                    <span
                        v-for="category in row.relationships.categories.data"
                        :key="category.id"
                    >{{ category.attributes.title }}</span>
                </td>
                <td>
                    <span
                        v-for="tag in row.relationships.tags.data"
                        :key="tag.id"
                    >{{ tag.attributes.title }}</span>
                </td>
                <td>{{ row.relationships.user.attributes.name }}</td>
                <td class="text-end">
                    <div
                        class="btn-group btn-group-sm"
                        role="group"
                        aria-label="Action button groups"
                    >
                        <button type="button" class="btn btn-outline-primary">View</button>
                        <router-link
                            :to="{name: 'articles.edit', params: {id: row.id}}"
                            class="btn btn-outline-primary"
                        >Edit</router-link>
                        <button
                            type="button"
                            class="btn btn-outline-danger"
                            @click="destroy(row)"
                        >Delete</button>
                    </div>
                </td>
            </tr>
        </template>
    </filterable>
</template>

<script>
import Filterable from "@/views/components/filterable";

export default {
    name: "articles-index",

    components: {
        filterable: Filterable,
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            collection: [],
            filterable: {
                model: this.$props.model,
            },
        };
    },

    methods: {
        destroy(article) {
            const result = confirm(
                `Do you want to remove this article [${article.attributes.title}]?`
            );

            result &&
                this.$props.model
                    .$delete({
                        params: {
                            id: article.id,
                        },
                    })
                    .then((response) => {
                        this.collection = this.collection.filter(
                            (item) => item.id !== article.id
                        );
                    });
        },
    },
};
</script>
