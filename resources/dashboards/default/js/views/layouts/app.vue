<template>
    <div class="app__body h-100">
        <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" :href="$root.app_url">{{ $root.app_name }}</a>
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
                            <router-link :to="{name: 'articles.index'}" class="nav-link">Articles</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{name: 'categories.index'}" class="nav-link">Categories</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="container">
            <router-view></router-view>
        </main>
        <loading-layer />
        <toast-container />
    </div>
</template>

<script>
import LoadingLayer from "@/views/components/loading-layer";
import Toast from "@/helpers/notification";
import ToastContainer from "@/views/components/toast-container";

export default {
    name: "app",

    components: {
        "loading-layer": LoadingLayer,
        "toast-container": ToastContainer,
    },

    mounted() {
        Toast.info({
            body: "See? Just like this.",
        });
    },
};
</script>
