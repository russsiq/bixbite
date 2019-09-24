// /**
//  * @source https://github.com/codekerala/laravel-and-vue.js-spa-Recipe-Box/blob/master/resources/assets/js/store/auth.js
//  */
//
//  import {
//      post
//  } from '@/helpers/api'
//
// export default {
//     state: {
//         user: null,
//         user_id: null,
//         api_token: null,
//         login_url: `${Pageinfo.api_url}/auth/login`,
//         logout_url: `${Pageinfo.api_url}/auth/logout`,
//     },
//
//     initialize() {
//         this.state.api_token = localStorage.getItem('api_token')
//         this.state.user_id = parseInt(localStorage.getItem('user_id'), 10)
//     },
//
//     set(api_token, user_id) {
//         localStorage.setItem('api_token', api_token)
//         localStorage.setItem('user_id', user_id)
//         this.initialize()
//     },
//
//     remove() {
//         localStorage.removeItem('api_token')
//         localStorage.removeItem('user_id')
//         this.initialize()
//     },
//
//     check() {
//         return 'string' == typeof this.state.api_token
//             && 'number' == typeof this.state.user_id
//     },
//
//     guest() {
//         return false === this.check()
//     },
// }
