<template>
  <div class="app__body h-100">
    <nav class="navbar sticky-top navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand" :href="$root.app_url">{{ $root.app_name }}</a>
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
  },

  methods: {},
};
</script>
