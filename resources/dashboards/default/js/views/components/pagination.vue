<template>
    <nav aria-label="Page navigation" role="navigation">
        <ul v-if="links.length" class="pagination justify-content-end">
            <li
                v-for="(link, index) in links"
                :key="'item-'+index"
                class="page-item"
                :class="{'active': link.active, 'disabled': !link.url}"
            >
                <a
                    v-if="link.url"
                    :href="link.url"
                    class="page-link"
                    @click.prevent="changePage(link)"
                    v-html="link.label"
                ></a>
                <span
                    v-else
                    class="page-link"
                    tabindex="-1"
                    aria-disabled="true"
                    v-html="link.label"
                ></span>
            </li>
        </ul>
    </nav>
</template>

<script>
import { Bus } from "@/store/bus.js";

export default {
    name: "pagination",

    data() {
        return {
            links: [],
            path: null,
        };
    },

    mounted() {
        Bus.$on("paginator.paginate", (meta) => {
            this.links = meta.links;
            this.path = meta.path;
        }).$on("paginator.reset", () => {
            this.links = [];
            this.path = null;
        });
    },

    methods: {
        changePage({ active, label, url }) {
            if (active) {
                return false;
            }

            const searchParams = new URLSearchParams(
                url.replace(this.path, "")
            );

            this.$emit("paginate", searchParams.get("page"));
        },
    },
};
</script>
