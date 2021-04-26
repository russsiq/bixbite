/**
 * Mutations that are used in this application module.
 */
const SET_META = 'SET_META';
const RESET_META = 'RESET_META';

export default {
    namespaced: true,

    /**
     * Defines the state being monitored for the module.
     */
    state: {
        container: {},
    },

    /**
     * Defines the getters used by the module.
     */
    getters: {
        /**
         * Вернуть все мета-данные.
         * @param  {[type]} state [description]
         * @return {[type]}       [description]
         */
        all: state => state.container,
    },

    /**
     * Defines the mutations used by the module.
     */
    mutations: {
        /**
         * Установить мета-данные.
         * @param  {[type]} state [description]
         * @param  {[type]} meta  [description]
         * @return {[type]}       [description]
         */
        [SET_META](state, meta) {
            state.container = {
                ...state.container,
                ...meta
            };
        },

        /**
         * Сбросить все мета-данные.
         * @NB: Нельзя сбрасывать все мета-данные!
         * @param  {[type]} state [description]
         * @return {[type]}       [description]
         */
        [RESET_META](state) {
            state.container = {};
        },
    },

    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {
        set(context, meta) {
            context.commit(SET_META, meta);
        },

        reset(context) {
            context.commit(RESET_META);
        },
    }
};
