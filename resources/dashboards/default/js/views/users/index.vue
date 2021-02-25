<template>
    <filterable v-bind="filterable" :collection.sync="collection">
        <template #title>Users</template>

        <template #first-group></template>

        <template #second-group>
            <button type="button" class="btn btn-outline-success">Create</button>
        </template>

        <template #thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col" class="text-end">Actions</th>
            </tr>
        </template>

        <template #trow="{row}">
            <tr :key="row.id">
                <th scope="row">{{ row.id }}</th>
                <td>{{ row.attributes.name }}</td>
                <td class="text-end">
                    <div
                        class="btn-group btn-group-sm"
                        role="group"
                        aria-label="Action button groups"
                    >
                        <button type="button" class="btn btn-outline-primary">View</button>
                        <router-link
                            :to="{name: 'users.edit', params: {id: row.id}}"
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
    name: "users-index",

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
        destroy(user) {
            const result = confirm(
                `Do you want to remove this user [${user.attributes.name}]?`
            );

            result &&
                this.$props.model
                    .$delete({
                        params: {
                            id: user.id,
                        },
                    })
                    .then((response) => {
                        this.collection = this.collection.filter(
                            (item) => item.id !== user.id
                        );
                    });
        },
    },
};
</script>
