'use strict';

/**
 * Load all of this project's JavaScript dependencies which includes
 * Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import Translator from './helpers/translator';

window.langProvider = new Translator(`${Pageinfo.app_url}/resources/skins/default/lang/`, Pageinfo.locale)

// Configure ajax provider.
Vue.prototype.$http = axios; // Ex.: this.$http.get(...)  === axios.get(...)


Vue.mixin({
    data() {
        return {
            lang: langProvider
        }
    },

    filters: {
        trans: function(key, replace) {
            return langProvider.trans(key, replace)
        },

        // choice: function(key, number, replace) {
        //     return langProvider.choice(key, number, replace)
        // },
    },

    methods: {
        trans: function(key, replace) {
            return this.lang.trans(key, replace)
        },

        // choice: function(key, number, replace) {
        //     return this.lang.choice(key, number, replace)
        // },

        async loadFromJsonPath(module) {
            await this.lang.loadFromJsonPath(module)
        },
    }
});

// Adding Vue components to application.
import UploadFiles from './components/UploadFiles.vue';
import FilesAttaching from './components/FilesAttaching.vue';
import ImageUploader from './components/ImageUploader.vue';
//import BxbEditor from './components/BxbEditor.vue';
import QuillEditor from './components/QuillEditor.vue';

Vue.component('upload-files', UploadFiles);
Vue.component('files-attaching', FilesAttaching);
Vue.component('image-uploader', ImageUploader);
//Vue.component('bxb-editor', BxbEditor);
Vue.component('quill-editor', QuillEditor);

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
        article: {
            id: 0,
            content: '',
        },
    },

    methods: {
        updateArticleContent(value) {
            console.log(value)
            this.article.content = value
        }
    }
});

// Make some vue plugins methods to global.
window.LoadingLayer = app.$loading;
window.ScrollToTop = app.$scrolling;
window.Notification = app.$notification;

// If necessary, activate the components immediately.
window.ScrollToTop.show({active: true});
