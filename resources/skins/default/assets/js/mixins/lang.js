export default {
    data() {
        return {
            lang: translator,
        }
    },

    methods: {
        trans(key) {
            return this.lang.trans(key)
        }
    }
}
