<template>
    <div class="filterable container my-5">
        <div class="card">
            <div class="card-header">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <h5 class="card-title d-inline-block lh-base fw-bold m-0 p-1">
                        <slot name="title"></slot>
                    </h5>
                    <div class="btn-group ms-auto" role="group" aria-label="First group">
                        <slot name="first-group"></slot>
                    </div>
                    <div class="btn-group ms-auto" role="group" aria-label="Second group">
                        <slot name="second-group"></slot>
                    </div>
                </div>
            </div>

            <div class="card-body table-responsive">
                <table v-if="collection && collection.length" class="table table-sm m-0">
                    <thead>
                        <slot name="thead"></slot>
                    </thead>
                    <tbody>
                        <slot name="trow" v-for="item in collection" :row="item"></slot>
                    </tbody>
                </table>

                <div v-else class="alert alert-info mb-0" role="status">No content.</div>
            </div>

            <div v-show="collection && collection.length" class="card-footer text-muted">
                <pagination @paginate="changePage" />
            </div>
        </div>
    </div>
</template>

<script>
import debounce from "lodash/debounce";
import { Bus } from "@/store/bus.js";
import Pagination from "@/views/components/pagination";

export default {
    name: "filterable",

    components: {
        pagination: Pagination,
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
        collection: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            query: {
                'page[number]': 1,
                'page[size]': 8,
            },
        };
    },

    mounted() {
        this.changePage({
            number: this.$route.query['page[number]'] || this.query['page[number]'],
            size: this.$route.query['page[size]'] || this.query['page[size]'],
        });

        /**
         * Instead of constantly calling the filter function,
         * we create an observer that will track changes in queries,
         * basically by changing the page number.
         */
        this.queryWatcher = this.$watch(
            "query",
            debounce(this.applyChange, 1000),
            {
                immediate: true,
                deep: true,
            }
        );
    },

    methods: {
        changePage({ number, size }) {
            this.query = {
                ...this.query,
                'page[number]': parseInt(number, 10),
                'page[size]': parseInt(size, 10),
            }
        },

        applyChange() {
            this.fetch({
                // ...this.filters,
                ...this.query,
            });
        },

        fetch(params) {
            this.$props.model.$fetch({params}).then(this.fillTable);
        },

        fillTable(collection) {
            this.$emit("update:collection", collection);
        },
    },
};
</script>
