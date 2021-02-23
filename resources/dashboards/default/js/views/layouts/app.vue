<template>
    <div class="app__body">
        <navbar />
        <main class="container">
            <router-view></router-view>
        </main>
        <loading-layer />
        <toast-container />
    </div>
</template>

<script>
import LoadingLayer from "@/views/components/loading-layer";
import Navbar from "@/views/components/navbar";
import ToastContainer from "@/views/components/toast-container";

export default {
    name: "app",

    components: {
        "loading-layer": LoadingLayer,
        "navbar": Navbar,
        "toast-container": ToastContainer,
    },
};
</script>
