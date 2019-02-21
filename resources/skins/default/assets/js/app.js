'use strict';

/**
 * Load all of this project's JavaScript dependencies which includes
 * Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';

// Configure ajax provider.
Vue.prototype.$http = axios; // Ex.: this.$http.get(...)  === axios.get(...)

const translations = {}
// NOT USED because return wrong namespace.
// let requireLangContext = require.context(/* webpackChunkName: "lang-[request]" */ '../../lang/', true, /ru\.json$/, 'lazy')

Vue.prototype.$loadLang = async function(module) {
    let fileName = module ? `${module}/ru.json` : `ru.json`

    const response = await axios.get(`../../../resources/skins/default/lang/${fileName}`)

    Object.assign(translations, response.data)
}

Vue.mixin({
    methods: {
        lang(key) {
            return translations[key] || key
        }
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

    created() {
        this.$loadLang()
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
