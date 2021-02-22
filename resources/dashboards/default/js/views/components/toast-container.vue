<template>
  <div
    class="toast-container position-fixed bottom-0 end-0 p-3"
    style="z-index: 5"
  >
    <div
      v-for="toast in toasts"
      :id="toast.id"
      :key="toast.id"
      class="toast fade"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
    >
      <div class="toast-header">
        <strong class="me-auto">{{ toast.header || $root.app_name }}</strong>
        <small class="text-muted">{{ toast.time }}</small>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="toast"
          aria-label="Close"
        ></button>
      </div>
      <div class="toast-body">{{ toast.body }}</div>
    </div>
  </div>
</template>

<script>
import { Toast } from "bootstrap";
import { Bus } from "@/bus.js";

export default {
  name: "toast-container",

  data() {
    return {
      toasts: [],
    };
  },

  watch: {},
  mounted() {
    Bus.$on("toastShow", (toast) => {
      const id = Math.random().toString(36).slice(2);

      this.toasts.push({
        id: `toast-${id}`,
        time: new Date().toLocaleTimeString(),
        ...toast,
      });
    });

    this.$watch("toasts", this.createToast);
  },

  methods: {
    createToast(newVal, oldVal) {
      const [current] = newVal.slice(-1);

      this.$nextTick(() => {
        const instance = new Toast(document.getElementById(current.id), {
          animation: true,
          autohide: false,
          delay: 8000,
        });

        instance.show();
      });
    },
  },
};
</script>
