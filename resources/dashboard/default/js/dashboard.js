'use strict';

require('./bootstrap');

import {
    Alert,
    Button,
    Carousel,
    Collapse,
    Dropdown,
    Modal,
    Popover,
    ScrollSpy,
    Tab,
    Toast,
    Tooltip,
} from 'bootstrap';

import Vue from 'vue';

Vue.config.productionTip = false;

import router from './router';

import App from './views/layouts/app.vue';

const app = new Vue({
    el: '#dashboard',
    components: {
        'app': App
    },
    router,
    template: '<app></app>',
});
