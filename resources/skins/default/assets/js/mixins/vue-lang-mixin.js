export default {
    data() {
        return {
            // Hook to wait while the common language file has been loaded.
            langProvider
        }
    },

    filters: {
        trans(key, replace) {
            return langProvider.trans(key, replace)
        },

        // choice(key, number, replace) {
        //     return langProvider.choice(key, number, replace)
        // },
    },

    methods: {
        __(key, replace) {
            return this.trans(key, replace)
        },

        trans(key, replace) {
            return langProvider.trans(key, replace)
        },

        // choice(key, number, replace) {
        //     return langProvider.choice(key, number, replace)
        // },

        async loadFromJsonPath(module) {
            await langProvider.loadFromJsonPath(module)
        },
    }
}
