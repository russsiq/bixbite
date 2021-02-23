<template>
    <div class="container mt-5">
        <div v-if="loading" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else class="card">
            <h6 class="card-header">Articles</h6>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm m-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Categories</th>
                            <th scope="col">Tags</th>
                            <th scope="col">User</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="article in articles" :key="article.id">
                            <th scope="row">{{ article.id }}</th>
                            <td>{{ article.attributes.title }}</td>
                            <td>
                                <span
                                    v-for="category in article.relationships.categories.data"
                                    :key="category.id"
                                >{{ category.attributes.title }}</span>
                            </td>
                            <td>
                                <span
                                    v-for="tag in article.relationships.tags.data"
                                    :key="tag.id"
                                >{{ tag.attributes.title }}</span>
                            </td>
                            <td>{{ article.relationships.user.attributes.name }}</td>
                            <td>
                                <div
                                    class="btn-group btn-group-sm"
                                    role="group"
                                    aria-label="Action button groups"
                                >
                                    <button type="button" class="btn btn-outline-primary">View</button>
                                    <button type="button" class="btn btn-outline-primary">Edit</button>
                                    <button type="button" class="btn btn-outline-danger">Delete</button>
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
    name: "articles-index",

    data() {
        return {
            loading: true,
            articles: [],
        };
    },

    mounted() {
        axios.get(this.$root.api_url + "/articles").then((response) => {
            this.articles = response.data.data;
            console.log(this.articles);
            this.loading = false;
        });
    },
};
</script>
