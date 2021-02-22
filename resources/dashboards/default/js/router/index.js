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
    base: 'dashboard',
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

export default router;
