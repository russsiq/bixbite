/**
 * Builds the data store from all of the modules for the App.
 */
import Vue from 'vue'
import Vuex from 'vuex'

// Install Vuex plugin on Vue with global method.
Vue.use(Vuex)

// Import Vuex modules.
import modules from './modules'

// Import Vuex plugins.
import VuexORM from '@vuex-orm/core'
import VuexORMAxios from '@vuex-orm/plugin-axios'
import database from './database'
import http from './axios-request-config'

VuexORM.use(VuexORMAxios, {
    axios: http,
    database,
    // http
})

// Instantiate and exports our data store.
export default new Vuex.Store({
    namespaced: true,
    strict: process.env.NODE_ENV !== 'production',
    modules: modules,
    plugins: [
        VuexORM.install(database),
    ],
})
