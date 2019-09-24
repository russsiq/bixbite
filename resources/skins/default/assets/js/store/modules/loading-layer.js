/**
 * Mutations that are used in this application module.
 */
const SHOW_LOADING_LAYER = 'SHOW_LOADING_LAYER';
const HIDE_LOADING_LAYER = 'HIDE_LOADING_LAYER';

export default {
    namespaced: true,

    /**
     * Defines the state being monitored for the module.
     */
    state: {
        show: false,
    },

    /**
     * Defines the getters used by the module.
     */
    getters: {
        show: state => state.show,
    },

    /**
     * Defines the mutations used by the module.
     */
    mutations: {
        [SHOW_LOADING_LAYER](state) {
            state.show = true;
        },

        [HIDE_LOADING_LAYER](state) {
            state.show = false;
        },
    },

    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {
        show(context){
    		context.commit(SHOW_LOADING_LAYER);
    	},

    	hide(context){
    		context.commit(HIDE_LOADING_LAYER);
    	},
    }
};
