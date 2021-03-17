<template>
    <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" :href="appUrl">{{ appName }}</a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#main-navbar"
                aria-controls="main-navbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="main-navbar" class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" exact>Home</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="indexPage('articles')" class="nav-link">Articles</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="indexPage('categories')" class="nav-link">Categories</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="indexPage('comments')" class="nav-link">Comments</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="indexPage('tags')" class="nav-link">Tags</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="indexPage('users')" class="nav-link">Users</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: "navbar",

    data() {
        return {
            appUrl: this.$root.app_url,
            appName: this.$root.app_name,
        };
    },

    computed: {
        indexPage() {
            return (entity) => ({
                name: `${entity}.index`,
                query: {
                    'page[number]': 1,
                    'page[size]': 8,
                },
            });
        },
    },
};
</script>
