<template>
    <div class="filterable">
        <div class="card my-5">
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
                <table v-if="collection.length" class="table table-sm m-0">
                    <thead>
                        <slot name="thead"></slot>
                    </thead>
                    <tbody>
                        <slot name="trow" v-for="item in collection" :row="item"></slot>
                    </tbody>
                </table>

                <div v-else class="alert alert-info mb-0">No content.</div>
            </div>

            <div v-show="collection.length" class="card-footer text-muted">
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
    },

    data() {
        return {
            collection: [],
            query: {
                page: 1,
            },
        };
    },

    mounted() {
        this.changePage(this.$route.query.page || 1);

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
        fillTable(collection) {
            this.collection = collection;
        },

        changePage(page) {
            this.query.page = parseInt(page, 10);
        },

        applyChange() {
            this.collection = [];

            this.$props.model.$fetch(this.query).then(this.fillTable);
        },
    },
};
</script>
