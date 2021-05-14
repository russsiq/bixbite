<!-- @source https://github.com/codekerala/Laravel-5.6-and-Vue.j-2-Dataviewer-Advanced-Filter -->

<template>
<div class="filterable">
    <div class="card d-print-none">
        <div class="card-header d-flex">
            <slot name="preaction"></slot>
        </div>
    </div>

    <div v-if="active" class="card">
        <!-- <div class="card-header">
            <div class="card-title">
                <span>Customers match</span>
                <select class="form-select" v-model="query.filter_match">
                    <option value="and">{{ 'All' | trans }}</option>
                    <option value="or">{{ 'Any' | trans }}</option>
                </select>
                <span>of the following:</span>
            </div>
        </div> -->

        <div class="card-body filter">
            <div v-for="(filter, index) in filterCandidates" :key="index" class="row">
                <div class="col col-md-4 mb-3 filter-column">
                    <div class="input-group">
                        <select class="form-select" v-model="filter.column" @change="selectColumn(index)">
                            <option value="" disabled selected>{{ 'Select a filter' | trans }}</option>
                            <optgroup v-for="(table, tableName) in allowedFilters" :key="tableName" :label="tableName | title | trans">
                                <option v-for="(column, columnName) in table" :key="columnName" :value="columnName">{{ columnName | title | trans }}</option>
                            </optgroup>
                        </select>
                        <button type="button" @click="removeFilter(index)" class="filter-remove btn btn-outline-secondary">x</button>
                    </div>
                </div>

                <div class="col col-md-4 mb-3 filter-operator">
                    <template v-if="filter.column">
                        <select class="form-select" v-model="filter.operator" @change="selectOperator(index)">
                            <option v-for="(operator, index) in fetchOperators(filter)" :key="index" :value="operator.name">{{ operator.title | trans }}</option>
                        </select>
                    </template>
                </div>

                <div class="col col-md-4 mb-3 filter-query">
                    <div class="input-group">
                        <template v-if="filter.component === 'single'">
                            <input type="text" v-model="filter.query_1" class="form-control" />
                            <div class="invalid-feedback">Valid first name is required.</div>
                        </template>
                        <template v-if="filter.component === 'double'">
                            <input type="text" v-model="filter.query_1" class="form-control" />
                            <input type="text" v-model="filter.query_2" class="form-control" />
                        </template>
                        <template v-if="filter.component === 'enum'">
                            <select class="form-select" v-model="filter.query_1">
                                <option v-for="(enumValue, index) in filter.values" :key="index" :value="enumValue">{{ enumValue | trans }}</option>
                            </select>
                        </template>
                        <template v-if="filter.component === 'boolean'">
                            <select class="form-select" v-model="filter.query_1">
                                <option value="0">Нет</option>
                                <option value="1">Да</option>
                            </select>
                        </template>
                        <template v-if="filter.component === 'datetime_1'">
                            <input type="text" v-model="filter.query_1" class="form-control">
                            <select class="form-select" v-model="filter.query_2">
                                <option value="hours">{{ 'hours' | trans }}</option>
                                <option value="days">{{ 'days' | trans }}</option>
                                <option value="months">{{ 'months' | trans }}</option>
                                <option value="years">{{ 'years' | trans }}</option>
                            </select>
                        </template>
                        <template v-if="filter.component === 'datetime_2'">
                            <select class="form-select" v-model="filter.query_1">
                                <option value="yesterday">{{ 'yesterday' | trans }}</option>
                                <option value="today">{{ 'today' | trans }}</option>
                                <option value="tomorrow">{{ 'tomorrow' | trans }}</option>
                                <option value="last_month">{{ 'last month' | trans }}</option>
                                <option value="this_month">{{ 'this month' | trans }}</option>
                                <option value="next_month">{{ 'next month' | trans }}</option>
                                <option value="last_year">{{ 'last year' | trans }}</option>
                                <option value="this_year">{{ 'this year' | trans }}</option>
                                <option value="next_year">{{ 'next year' | trans }}</option>
                            </select>
                        </template>
                    </div>
                </div>
            </div>

            <div class="filter-controls mb-3 d-flex">
                <button type="button" @click="addFilter" class="btn btn-outline-primary me-auto">{{ 'Add filter' | trans }}</button>
                <template v-if="filterCandidates.length && filterCandidates[0].query_1">
                    <button type="button" @click="applyFilter" class="btn btn-outline-success">{{ 'Apply' | trans }}</button>
                </template>
                <button type="reset" v-if="appliedFilters.length > 0" @click="resetFilter" class="btn btn-outline-dark ms-2">{{ 'Reset' | trans }}</button>
            </div>
        </div>
    </div>

    <div class="card card-table">
        <div v-if="collection.length" class="card-header">
            <div class="row">
                <div class="col col-md-6">
                    <pagination class="justify-content-eend" @paginate="changePage"></pagination>
                </div>
                <div class="col col-md-6">
                    <div class="d-flex">
                        <div class="has-float-label ms-auto me-2">
                            <label>{{ 'Count' | trans }}</label>
                            <select class="form-select" v-model="query.limit" @input="changePage(1)" :disabled="loading">
                                <option v-for="(limit, index) in limits" :key="index" :value="limit">{{ limit }}</option>
                            </select>
                        </div>

                        <div class="has-float-label">
                            <label>{{ 'Order by' | trans }}</label>
                            <div class="input-group">
                                <select class="form-select" v-model="query.order_column" @input="changePage(1)" :disabled="loading">
                                    <template v-for="(column, index) in orderableColumns">
                                        <option :key="index" :value="column">{{ column | title | trans }}</option>
                                    </template>
                                </select>
                                <button type="button" class="btn btn-outline-secondary bg-white" @click="changeOrderDirection">
                                    <span v-if="query.order_direction === 'asc'">&uarr;</span>
                                    <span v-else>&darr;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LIST OF COLLECTION -->
        <div class="card-body table-responsive">
            <table v-if="collection.length" class="table table-sm table-hover">
                <thead><slot name="thead"></slot></thead>
                <tbody><slot name="row" v-for="item in collection" :row="item"></slot></tbody>
                <tfoot><slot name="tfoot"></slot></tfoot>
            </table>
            <p v-else-if="loading" class="alert alert-info text-center">Список загружается, пожалуйста, подождите ...</p>
            <p v-else class="alert alert-info text-center">Нет информации для отображения.</p>
        </div>

        <div v-if="collection.length" class="card-footer">
            <div class="row">
                <div class="col col-md-6">
                    <pagination class="justify-content-eend" @paginate="changePage"></pagination>
                </div>
                <div v-if="massAction" class="col col-md-6">
                    <div class="d-flex d-print-none">
                        <div class="has-float-label ms-auto">
                            <label for="">{{ 'Mass action' | trans }}</label>
                            <slot name="action"></slot>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <small> Showing {{collection.from}} - {{collection.to}} of {{collection.total}} entries.</small>
            <button class="btn" :disabled="!collection.prev_page_url || loading" @click="prevPage">&laquo; Prev</button>
            <button class="btn" :disabled="!collection.next_page_url || loading" @click="nextPage">Next &raquo;</button> -->
        </div>
    </div>
</div>
</template>

<script type="text/javascript">
import debounce from 'lodash/debounce';
import {
    mapGetters
} from 'vuex';

import Pagination from '@/views/components/pagination';

export default {
    components: {
        'pagination': Pagination,
    },

    props: {
        active: Boolean,
        massAction: Boolean,
        model: {
            type: Function,
            required: true
        },
        value: Array
    },

    data() {
        return {
            filterCandidates: [],
            queryWatcher: null,
            query: {
                order_column: 'id',
                order_direction: 'desc',
                filter_match: 'and',
                limit: 10,
                page: 1
            },
            limits: [5, 10, 20, 50],
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
            loading: 'loadingLayer/show',
        }),

        collection() {
            return this.$props.value || [];
        },

        orderableColumns() {
            return this.meta.orderableColumns || [];
        },

        allowedFilters() {
            // На данный момент фильтрация доступна только для модели Article.
            return this.$props.model.state().allowedFilters || {};
            // return this.meta.allowedFilters || {};
        },

        fetchOperators() {
            return (filter) => {
                return this.availableOperators()
                    .filter((operator) => {
                        return filter.column && operator.parent.includes(filter.type)
                    });
            }
        },

        appliedFilters() {
            return this.filterCandidates.filter((item) => !!item.column);
        },

        filters() {
            const f = {};

            this.appliedFilters.map(function(filter, i) {
                f[`f[${i}][column]`] = filter.column;
                f[`f[${i}][operator]`] = filter.operator;
                f[`f[${i}][query_1]`] = filter.query_1;
                if (filter.query_2) {
                    f[`f[${i}][query_2]`] = filter.query_2;
                }
            });

            return f;
        },
    },

    filters: {
        title(value) {
            if (!value) return '';
            value = value.toString().replace(/_/g, ' ').replace('.', ' ');
            value = value.charAt(0).toUpperCase() + value.slice(1);

            return value;
        },
    },

    mounted() {
        this.changePage(parseInt(this.$router.currentRoute.query.page, 10) || 1);

        /**
         * Instead of constantly calling the filter function,
         * we create an observer that will track changes in queries,
         * basically by changing the page number.
         */
        this.queryWatcher = this.$watch('query', debounce(this.applyChange, 1000), {
            immediate: true,
            deep: true
        });
    },

    methods: {
        changeOrderDirection() {
            this.query = Object.assign({}, this.query, {
                order_direction: 'desc' === this.query.order_direction ? 'asc' : 'desc',
                page: 1,
            });
        },

        changePage(page = 1) {
            this.query.page = parseInt(page, 10);
        },

        selectColumn(i) {
            // 1. Get column from v-model select.
            const column = this.filterCandidates[i].column || null;

            if (!column) {
                this.removeFilter(i);
                return;
            }

            // 2. Get current filter from allowed filters by column name.
            const table = Object.keys(this.allowedFilters)
                .find(key => this.allowedFilters[key].hasOwnProperty(column));

            // 3. Set type and values.
            const filter = this.allowedFilters[table][column];
            this.filterCandidates[i].type = filter.type;
            this.filterCandidates[i].values = !!filter.values ? filter.values.split(',') : null;

            // 4. Set the first available operator.
            const {name} = this.availableOperators()
                .find(item => item.parent.includes(filter.type));
            this.filterCandidates[i].operator = name;

            this.selectOperator(i);
        },

        selectOperator(i) {
            // 1. Get operator from v-model select.
            const operator = this.filterCandidates[i].operator || null;

            if (!operator) {
                return;
            }

            // 2. Get component.
            const {component} = this.availableOperators()
                .find(item => item.name === operator && item.parent.includes(this.filterCandidates[i].type));

            // 3. Define default query.
            let query_1 = null,
                query_2 = null;

            switch (component) {
                case 'single':
                case 'double':
                    break;
                case 'boolean':
                    query_1 = 1;
                    break;
                case 'enum':
                    query_1 = this.filterCandidates[i].values[0] || null;
                    break;
                case 'datetime_1':
                    query_1 = 28;
                    query_2 = 'days';
                    break;
                case 'datetime_2':
                    query_1 = 'today';
                    break;
            }

            // 4. Set component type and query.
            this.filterCandidates[i].component = component;
            this.filterCandidates[i].query_1 = query_1;
            this.filterCandidates[i].query_2 = query_2;
        },

        addFilter() {
            this.filterCandidates.push({
                column: '',
                type: null,
                component: null,
                operator: '',
                values: null,
                query_1: null,
                query_2: null,
            });
        },

        removeFilter(i) {
            this.filterCandidates.splice(i, 1);
            this.applyFilter();
        },

        resetFilter() {
            this.filterCandidates.splice(0);
            this.applyFilter();
        },

        applyFilter() {
            // Always reset page to first.
            this.query = Object.assign({}, this.query, {
                page: 1
            });
        },

        applyChange() {
            this.$emit('apply:change', {
                ...this.filters,
                ...this.query
            });
        },

        /**
         * На данный момент 19 доступных операторов.
         * @return {object} Массив доступных операторов.
         */
        availableOperators() {
            return [
                // Count and string.
                {title: 'equal to', name: 'equal_to', parent: ['numeric', 'string'], component: 'single'},
                {title: 'not equal to', name: 'not_equal_to', parent: ['numeric', 'string'], component: 'single'},
                // Boolean.
                {title: 'equal to', name: 'boolean', parent: ['boolean'], component: 'boolean'},
                // Relation count.
                {title: 'equal to', name: 'equal_to_count', parent: ['counter'], component: 'single'},
                {title: 'not equal to', name: 'not_equal_to_count', parent: ['counter'], component: 'single'},
                {title: 'less than', name: 'less_than_count', parent: ['counter'], component: 'single'},
                {title: 'greater than', name: 'greater_than_count', parent: ['counter'], component: 'single'},
                // Count.
                {title: 'less than', name: 'less_than', parent: ['numeric'], component: 'single'},
                {title: 'greater than', name: 'greater_than', parent: ['numeric'], component: 'single'},
                {title: 'between', name: 'between', parent: ['numeric'], component: 'double'},
                {title: 'not between', name: 'not_between', parent: ['numeric'], component: 'double'},
                // String.
                {title: 'contains', name: 'contains', parent: ['string'], component: 'single'},
                {title: 'starts with', name: 'starts_with', parent: ['string'], component: 'single'},
                {title: 'ends with', name: 'ends_with', parent: ['string'], component: 'single'},
                // Enum.
                {title: 'equal to', name: 'equal_to', parent: ['enum'], component: 'enum'},
                {title: 'not equal to', name: 'not_equal_to', parent: ['enum'], component: 'enum'},
                // Timestamps.
                {title: 'in the past', name: 'in_the_past', parent: ['datetime'], component: 'datetime_1'},
                {title: 'in the next', name: 'in_the_next', parent: ['datetime'], component: 'datetime_1'},
                {title: 'in the period', name: 'in_the_period', parent: ['datetime'], component: 'datetime_2'},
            ];
        }
    }
}
</script>

<style lang="scss">
.table-responsive .table tfoot td,
.table-responsive .table tfoot th {
    border-top: 2px solid #dee2e6;
}
</style>
