<template>
    <filterable v-bind="filterable" :collection.sync="collection">
        <template #title>Categories</template>

        <template #first-group></template>

        <template #second-group>
            <router-link :to="{name: 'categories.create'}" class="btn btn-outline-success">Create</router-link>
        </template>

        <template #thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col" class="text-end">Actions</th>
            </tr>
        </template>

        <template #trow="{row}">
            <tr :key="row.id">
                <th scope="row">{{ row.id }}</th>
                <td>{{ row.attributes.title }}</td>
                <td class="text-end">
                    <div
                        class="btn-group btn-group-sm"
                        role="group"
                        aria-label="Action button groups"
                    >
                        <button type="button" class="btn btn-outline-primary">View</button>
                        <router-link
                            :to="{name: 'categories.edit', params: {id: row.id}}"
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
    name: "categories-index",

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
        destroy(category) {
            const result = confirm(
                `Do you want to remove this category [${category.attributes.title}]?`
            );

            result &&
                this.$props.model
                    .$delete({
                        params: {
                            id: category.id,
                        },
                    })
                    .then((response) => {
                        this.collection = this.collection.filter(
                            (item) => item.id !== category.id
                        );
                    });
        },
    },
};
</script>
