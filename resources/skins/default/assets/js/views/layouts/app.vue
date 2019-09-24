<template>
<div class="app__body">
    <page-header v-if="isLogged"></page-header>

    <main class="container">
        <router-view></router-view>
    </main>

    <loading-layer></loading-layer>
</div>
</template>

<script type="text/ecmascript-6">
import {
    mapGetters,
    mapActions
} from 'vuex'

import Category from '@/store/models/category';

import LoadingLayer from '@/views/components/loading-layer';
import PageHeader from '@/views/components/page-header';

export default {
    name: 'app',

    components: {
        'page-header': PageHeader,
        'loading-layer': LoadingLayer,
    },

    data() {
        return {}
    },

    computed: {
        ...mapGetters({
            isLogged: 'auth/isLogged',
        }),
    },

    created() {
        this.$scrolling.show();

        this.authInitialize()
            .then(() => {
                if (this.isLogged) {
                    // При создании экземпляра приложения
                    // загружаем по API список всех категорий.
                    Category.$fetch();
                } else if ('login' != this.$router.currentRoute.name) {
                    this.$router.push({
                        name: 'login'
                    });
                }
            })
            .catch(error => {
                console.error(error);
            });
    },

    methods: {
        ...mapActions({
            authInitialize: 'auth/initialize',
        })
    },
}
</script>
