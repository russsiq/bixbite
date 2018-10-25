'use strict';

/**
 * Load all of this project's JavaScript dependencies
 * which includes Vue and other libraries.
 */

try {
    require('./bootstrap');
} catch (e) {
    console.log(e)
}

// Import Vue components as plugins.
import LoadingLayer from 'bxb-loading-layer';
import ScrollToTop from 'bxb-scroll-to-top';
import Notification from 'bxb-notification';

// Install plugins to Vue application.
Vue.use(LoadingLayer);
Vue.use(ScrollToTop);
Vue.use(Notification);

// Create a fresh Vue application instance and attach it to the page.
const app = new Vue({
    el: '#app',

    data: {
        //
    },
});

// Make some vue plugins methods to global.
window.LoadingLayer = app.$loading;
window.ScrollToTop = app.$scrolling;
window.Notification = app.$notification;

// If necessary, activate the components immediately.
window.ScrollToTop.show({
    active: true
});
