export default {
    data() {
        return {
            app_name: Pageinfo.app_name,
            app_url: Pageinfo.app_url,
            app_dashboard: Pageinfo.app_dashboard,
        }
    },

    computed: {
        /**
         * Формирование адресов на публичные файлы скина админ. панели.
         * @param  {string}  path
         * @return {string}
         */
         dashboard() {
            return (path) => {
                return `${this.app_url}/dashboards/${this.app_dashboard}/${path}`.replace(/\/+$/, '');
            }
        },

        /**
         * Формирование адресов на основной сайт.
         * @param  {string}  path
         * @return {string}
         */
        url() {
            return (path) => {
                return `${this.app_url}/${path}`.replace(/\/+$/, '');
            }
        },
    },

    filters: {
        dateToString(value) {
            return !value ? '' : (new Date(value)).toLocaleString();
        }
    },

    // methods: {
    //     can(...args) {
    //         return args
    //     },
    //
    //     setting(...args) {
    //         return args
    //     },
    // }
}
