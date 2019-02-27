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

/* // Add a request interceptor.
window.axios.interceptors.request.use(
    function (config) {
        // Do something before request is sent.
        axios.$loading = LoadingLayer.show({active: true})
        
        return config
    },
    function (error) {
        // Do something with request error.
        
        return Promise.reject(error)
    }
);

// Add a response interceptor.
window.axios.interceptors.response.use(
    function (response) {
        // Do something with response data.
        
        return response
    },
    function (error) {
        // Do something with response error.
        axios.$loading.hide()
        
        return Promise.reject(error)
    }
); */
