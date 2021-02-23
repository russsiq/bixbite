/**
 * Builds the router from all of the routes for the App.
 */
import Vue from 'vue';
import VueRouter from 'vue-router';

// Install VueRouter plugin on Vue with global method.
Vue.use(VueRouter);

// Import app routes.
import routes from './routes';

// Instantiate and exports app router.
const router = new VueRouter({
    mode: 'history',
    base: BixBite.dashboard_base_url,
    routes: routes,
    linkActiveClass: 'active',
    pathToRegexOptions: {
        strict: true
    },
    scrollBehavior(to, from, savedPosition) {
        return {
            x: 0,
            y: 0
        }
    },
});

// В отличие от сторожевых хуков,
// в глобальные хуки не передаётся функция next,
// и на навигацию они повлиять не могут.
router.afterEach(function (to, from) {
    const titles = [
        `Dashboard ${BixBite.app_name}`,
    ];

    to.query.page && titles.unshift(`Page ${to.query.page}`);
    to.meta.title && titles.unshift(to.meta.title);

    document.title = titles.join(' · ');
});

export default router;
