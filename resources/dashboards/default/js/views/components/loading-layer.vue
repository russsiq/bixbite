<template>
    <div v-if="isActive" class="position-fixed bottom-0 start-0 m-3">
        <div class="spinner-grow text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>

<script>
import { Bus } from "@/store/bus.js";

export default {
    name: "loading-layer",

    data() {
        return {
            isActive: false,
        };
    },

    mounted() {
        Bus.$on("spinner.show", (spinner) => {
            this.isActive = true;
        }).$on("spinner.hide", (spinner) => {
            this.isActive = false;
        });
    },
};
</script>
