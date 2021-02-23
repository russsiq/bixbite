<template>
    <div class="container mt-5">
        <div class="card">
            <h6 class="card-header">Categories</h6>
            <div class="card-body table-responsive">
                <table v-if="categories.length" class="table table-sm m-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="category in categories" :key="category.id">
                            <th scope="row">{{ category.id }}</th>
                            <td>{{ category.attributes.title }}</td>
                            <td>
                                <div
                                    class="btn-group btn-group-sm"
                                    role="group"
                                    aria-label="Action button groups"
                                >
                                    <button type="button" class="btn btn-outline-primary">View</button>
                                    <button type="button" class="btn btn-outline-primary">Edit</button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger"
                                        @click="destroy(category)"
                                    >Delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "categories-index",

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            categories: [],
        };
    },

    mounted() {
        this.$props.model.$fetch().then(this.fillTable);
    },

    methods: {
        fillTable(collection) {
            this.categories = collection;
        },

        destroy(category) {
            // Получаем все данные по конкретной записи.
            category = this.categories.find((item) => item.id === category.id);

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
                        this.categories = this.categories.filter(
                            (item) => item.id !== category.id
                        );
                    });
        },
    },
};
</script>
