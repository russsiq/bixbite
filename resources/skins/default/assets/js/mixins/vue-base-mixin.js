export default {
    data() {
        return {
            app_name: Pageinfo.app_name,
            app_url: Pageinfo.app_url,
            app_skin: Pageinfo.app_skin,
        }
    },

    computed: {
        /**
         * Формирование адресов на публичные файлы скина админ. панели.
         * @param  {string}  path
         * @return {string}
         */
        skin() {
            return (path) => {
                return `${this.app_url}/skins/${this.app_skin}/${path}`.replace(/\/+$/, '');
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
