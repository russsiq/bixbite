<template>

<nav v-if="crumbs.length > 0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <router-link :to="{name: 'dashboard'}" :title="__('Home')"><i class="fa fa-home"></i></router-link>
        </li>

        <template v-for="(crumb, index) in crumbs">
            <li v-if="index == last" class="breadcrumb-item active">{{ __(crumb.title)}}</li>
            <li v-else class="breadcrumb-item">
                <router-link :to="{name: crumb.name, params: crumb.params}">{{ __(crumb.title)}}</router-link>
            </li>
        </template>
    </ol>
</nav>

</template>

<script type="text/ecmascript-6">
export default {
    props: {
        crumbs: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            last: null,
        }
    },

    watch: {
        crumbs: {
            deep: true,
            handler: function (val, oldVal) {
                this.last = this.$props.crumbs.length - 1
            }
        },
    }
}
</script>
