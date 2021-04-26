/**
 * Настройки для библиотеки `axios`, используемые по умолчанию.
 */

const config = {
    /**
     * Заголовки, посылаемые во всех запросах.
     * @type {Object}
     */
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': Pageinfo.csrf_token,
    },
};

// setCSRFProtection
if (! Pageinfo.csrf_token) {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// if (Pageinfo.api_token) {
//     config.headers['Authorization'] = 'Bearer ' + Pageinfo.api_token;
// }

export default config;
