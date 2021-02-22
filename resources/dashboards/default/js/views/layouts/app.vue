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
              <router-link :to="{ name: 'articles.index' }" class="nav-link"
                >Articles</router-link
              >
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <main class="container">
      <router-view></router-view>
    </main>
    <toast-container />
  </div>
</template>

<script>
import ToastContainer from "@/views/components/toast-container";
import { Bus } from "@/bus.js";

export default {
  name: "app",

  components: {
    "toast-container": ToastContainer,
  },

  data() {
    return {};
  },

  computed: {},

  mounted() {
    Bus.$emit("toastShow", {
      //   header: "BixBite",
      body: "See? Just like this.",
    });

    console.log(this.$router);
  },

  methods: {},
};
</script>
