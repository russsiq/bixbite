'use strict';

/**
 * Load all of this project's JavaScript dependencies
 * which includes Vue and other libraries.
 */

try {
    // // Load jQuery and Bootstrap jQuery plugin.
    // window.$ = window.jQuery = require('jquery');
    // window.Popper = require('popper.js').default;
    // require('bootstrap');
} catch (e) {
    console.log(e)
}

// Load the axios HTTP library which allows to easily issue requests.
import Axios from './http';
window.Axios = Axios;

import Vue from 'vue';
import store from './store';

// Import Vue components as plugins.
import ScrollToTop from 'bxb-scroll-to-top';
import Notification from 'bxb-notification';

// Install plugins to Vue application.
Vue.use(ScrollToTop);
Vue.use(Notification);

import LoadingLayer from './components/loading-layer';

// Create a fresh Vue application instance and attach it to the page.
const app = new Vue({
    el: '#app',

    components: {
        'loading-layer': LoadingLayer,
    },

    // Register the Vuex store.
    store,

    data: {
        //
    },

    created() {
        this.$scrolling.show();
    },
});

// Make some vue plugins methods to global.
// window.LoadingLayer = app.$loading;
window.Notification = app.$notification;

import './script';
