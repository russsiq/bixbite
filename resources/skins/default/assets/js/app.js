'use strict';

/**
 * Load all of this project's JavaScript dependencies which includes
 * Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// Configure ajax provider.
Vue.prototype.$http = axios; // Ex.: this.$http.get(...)  === axios.get(...)

// Adding Vue components to application.
Vue.component('upload-files', require('./components/UploadFiles.vue'));
Vue.component('image-uploader', require('./components/ImageUploader.vue'));
Vue.component('bxb-editor', require('./components/BxbEditor.vue'));

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
    el: '#app'
});

// Make some vue plugins methods to global.
window.LoadingLayer = app.$loading;
window.ScrollToTop = app.$scrolling;
window.Notification = app.$notification;

// If necessary, activate the components immediately.
window.ScrollToTop.show({active: true});
