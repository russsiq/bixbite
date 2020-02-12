'use strict';

/**
 * Define language provider.
 */
import Translator from './helpers/translator';

window.langProvider = new Translator({
    locale: Pageinfo.locale,
    url: `${Pageinfo.app_url}/skins/${Pageinfo.app_skin}/lang/`,
});

window.__ = function(key, replace) {
    return langProvider.trans(key, replace);
}

/**
 * Load all of this project's JavaScript dependencies.
 */
require('./bootstrap');

import Vue from 'vue';

// Disable the tip in the browser console in production mode.
Vue.config.productionTip = false;

// Adding Vue mixins to application.
import VueLangMixin from '@/mixins/vue-lang-mixin';
import VueBaseMixin from '@/mixins/vue-base-mixin';

// Install mixins to Vue application.
Vue.mixin(VueLangMixin);
// !!! Not global installed. ???
Vue.mixin(VueBaseMixin);

// Adding Vue plugin to application.
import store from '@/store';
import router from '@/router';

// Import Vue components as plugins.
import ScrollToTop from 'bxb-scroll-to-top';
import Notification from 'bxb-notification';

// Install plugins to Vue application.
Vue.use(ScrollToTop);
Vue.use(Notification);

import App from '@/views/layouts/app.vue';

// Create a fresh Vue application instance and attach it to the page.
const app = new Vue({
    el: '#app',

    components: {
        'app': App
    },

    // Register the Vue router.
    router,

    // Register the Vuex store.
    store,

    template: '<app></app>',
});

// Make some vue plugins methods to global.
window.Notification = app.$notification;

// /**
//  * Adding Vue components to application.
//  */
// import upperFirst from 'lodash/upperFirst'
// import camelCase from 'lodash/camelCase'
//
// const requireComponent = require.context('./components', false, /\w+\.(vue)$/);
//
// requireComponent.keys()
//     .forEach(fileName => {
//         // Получение конфигурации компонента
//         const componentConfig = requireComponent(fileName)
//
//         // Получение имени компонента в PascalCase
//         const componentName = upperFirst(camelCase(
//             fileName.replace(/^\.\/(.*)\.\w+$/, '$1');
//         ));
//
//         // Глобальная регистрация компонента
//         Vue.component(componentName, componentConfig.default || componentConfig);
//     });
