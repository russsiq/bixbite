<template>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 5">
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
                <svg
                    class="bd-placeholder-img rounded me-2"
                    width="20"
                    height="20"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                    preserveAspectRatio="xMidYMid slice"
                    focusable="false"
                >
                    <rect width="100%" height="100%" :fill="`var(--bs-${toast.type})`" />
                </svg>
                <strong class="me-auto">{{ toast.header }}</strong>
                <small>{{ toast.time }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ toast.body }}</div>
        </div>
    </div>
</template>

<script>
import { Toast } from "bootstrap";
import { Bus } from "@/store/bus.js";

export default {
    name: "toast-container",

    data() {
        return {
            toasts: [],
        };
    },

    mounted() {
        Bus.$on("toast.show", this.appendToast);
    },

    methods: {
        appendToast(toast) {
            this.toasts.push(toast);

            this.$nextTick(() => {
                const toastElement = document.getElementById(toast.id);

                const instance = new Toast(toastElement, toast);

                instance.show();

                toastElement.addEventListener("hidden.bs.toast", (event) => {
                    this.toasts = this.toasts.filter(
                        (item) => item.id !== toast.id
                    );
                });
            });
        },
    },
};
</script>
