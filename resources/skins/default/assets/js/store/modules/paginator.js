/**
 * Mutations that are used in this application module.
 */
const SET_PAGINATION = 'SET_PAGINATION';
const RESET_PAGINATION = 'RESET_PAGINATION';

const PAGINATION_CURRENT_PAGE = 1;
const PAGINATION_PAGE_RANGE = 2;
const PAGINATION_PER_PAGE = 15;
const PAGINATION_TOTAL = 0;

export default {
    namespaced: true,

    /**
     * Defines the state being monitored for the module.
     */
    state: {
        current_page: PAGINATION_CURRENT_PAGE,
        page_range: PAGINATION_PAGE_RANGE,
        per_page: PAGINATION_PER_PAGE,
        total: PAGINATION_TOTAL,
    },

    /**
     * Defines the getters used by the module.
     */
    getters: {
        current_page: state => state.current_page,
        page_range: state => state.page_range,
        per_page: state => state.per_page,
        total: state => state.total,
    },

    /**
     * Defines the mutations used by the module.
     */
    mutations: {
        [SET_PAGINATION](state, meta) {
            state.current_page = parseInt(meta.current_page, 10);
            // state.page_range = parseInt(meta.page_range, 10);
            state.per_page = parseInt(meta.per_page, 10);
            state.total = parseInt(meta.total, 10);
        },

        [RESET_PAGINATION](state) {
            state.current_page = PAGINATION_CURRENT_PAGE;
            state.page_range = PAGINATION_PAGE_RANGE;
            state.per_page = PAGINATION_PER_PAGE;
            state.total = PAGINATION_TOTAL;
        },
    },

    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {
        paginate(context, meta) {
            context.commit(SET_PAGINATION, meta);
        },

        resetPaginator(context) {
            context.commit(RESET_PAGINATION);
        },
    }
};
