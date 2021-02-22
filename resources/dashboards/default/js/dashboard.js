'use strict';

require('./bootstrap');

import Vue from 'vue';

Vue.config.productionTip = false;

import router from './router';

import App from './views/layouts/app.vue';

const app = new Vue({
    el: '#dashboard',
    components: {
        'app': App
    },
    data() {
        return {
            ...BixBite,
        }
    },
    router,
    template: '<app></app>',
});
