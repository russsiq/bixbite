/**
 * Builds the router from all of the routes for the App.
 */
import Vue from 'vue'
import VueRouter from 'vue-router'

// Install VueRouter plugin on Vue with global method.
Vue.use(VueRouter)

// Import app routes.
import routes from './routes'

// Define the base address of our panels.
const base_url = `${Pageinfo.panel}`
    .replace(/^https?:\//, '')
    .replace(/^\/localhost/, '')

// Instantiate and exports app router.
const router = new VueRouter({
    mode: 'history',
    base: base_url,
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
router.afterEach((to, from) => {
    document.title = to.meta.title || 'Панель управления';

    if (to.query.page) {
        document.title += ' — Страница '+ to.query.page
    }
});

// 14) Как защитить какой-то маршрут от несанкционированного доступа?
// Его можно сделать внутри компонента или в глобальных guards.
// router.beforeEach((to, from, next) => {
//     if (to.isProtected() && !haveAccess(user)) {
//         next(false)
//     }
//     next()
// })

export default router;
