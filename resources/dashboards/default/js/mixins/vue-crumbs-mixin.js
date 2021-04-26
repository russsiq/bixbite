export default {
    data() {
        return {
            crumbs: [],
        }
    },

    watch: {
        crumbs: {
            deep: true,
            handler: function (val, oldVal) {
                this.$parent.crumbs = val
                // .filter(function(value, index, self) {
                //     return self.indexOf(value) === index
                // })
            }
        },
    },

    mounted() {
        this.$parent.crumbs = this.crumbs
    },

    methods: {
        pushCrumb(title, name) {
            this.crumbs.push({title, name})
        }
    }
}