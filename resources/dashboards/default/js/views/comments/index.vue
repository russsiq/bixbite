<template>
    <filterable v-bind="filterable">
        <template #title>Comments</template>

        <template #first-group></template>

        <template #second-group>
            <button type="button" class="btn btn-outline-success">Create</button>
        </template>

        <template #thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Content</th>
                <th scope="col" class="text-end">Actions</th>
            </tr>
        </template>

        <template #trow="{row}">
            <tr :key="row.id">
                <th scope="row">{{ row.id }}</th>
                <td>{{ row.attributes.content }}</td>
                <td class="text-end">
                    <div
                        class="btn-group btn-group-sm"
                        role="group"
                        aria-label="Action button groups"
                    >
                        <button type="button" class="btn btn-outline-primary">View</button>
                        <router-link
                            :to="{name: 'comments.edit', params: {id: row.id}}"
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
    name: "comments-index",

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
            filterable: {
                model: this.$props.model,
            },
        };
    },

    methods: {
        destroy(comment) {
            const result = confirm(
                `Do you want to remove this comment [${comment.id}]?`
            );

            result &&
                this.$props.model
                    .$delete({
                        params: {
                            id: comment.id,
                        },
                    })
                    .then((response) => {
                        this.comments = this.comments.filter(
                            (item) => item.id !== comment.id
                        );
                    });
        },
    },
};
</script>
