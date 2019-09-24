/**
 * Builds the data store from all of the modules for the App.
 */
import Vue from 'vue';
import Vuex from 'vuex';

// Install Vuex plugin on Vue with global method.
Vue.use(Vuex);

// Import Vuex modules.
import modules from './modules';

// Instantiate and exports our data store.
export default new Vuex.Store({
    namespaced: true,
    strict: process.env.NODE_ENV !== 'production',
    modules: modules,
})
