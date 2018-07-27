
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('upload-files',       require('./components/UploadFiles.vue'));
Vue.component('image-uploader',     require('./components/ImageUploader.vue'));

// Vue.component('files-attaching',     require('./components/FilesAttaching.vue'));

Vue.component('bxb-editor', require('./components/BxbEditor.vue'));

const app = new Vue({
    el: '#app'
});
