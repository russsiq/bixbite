// Indicate what all ajax requests was made with XMLHttpRequest.
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$.ajaxSetup({headers: {'X-Requested-With': 'XMLHttpRequest'}});

// Register the CSRF Token as a common header with Axios and jQuery.
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    window.$.ajaxSetup({headers: {'X-CSRF-TOKEN': token.content}});
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Configure ajax provider.
Vue.prototype.$http = axios; // Ex.: this.$http.get(...)  === axios.get(...)
