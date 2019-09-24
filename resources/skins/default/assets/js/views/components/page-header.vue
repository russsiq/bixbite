<template>
<header>
    <!-- Fixed navbar -->
    <nav class="navbar fixed-top navbar-expand navbar-dark bg-primary justify-content-between">
        <a :href="app_url" target="_blank" class="navbar-brand">
            <i class="fa fa-external-link d-sm-none"></i> <span class="d-none d-sm-inline">{{ app_name }}</span>
        </a>

        <div id="navbar_main" class="collapse navbar-collapse">
            <ul class="navbar-nav d-none d-md-flex">
                <li class="nav-item d-sm-none d-md-block">
                    <router-link :to="{name:'dashboard'}" class="nav-link" exact><i class="fa fa-home"></i></router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name:'articles'}" class="nav-link">Записи</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name:'categories'}" class="nav-link">Категории</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name:'notes'}" class="nav-link">Заметки</router-link>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-sm-block d-md-none">
                    <router-link :to="{name:'dashboard'}" class="nav-link"><i class="fa fa-home"></i></router-link>
                </li>
                <!-- <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" title="Создать" data-toggle="dropdown"><i class="fa fa-plus"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <router-link :to="{name:'articles.create'}" class="dropdown-item">Запись</router-link>
                        <router-link :to="{name:'categories.create'}" class="dropdown-item">Категория</router-link>
                        <router-link :to="{name:'notes.create'}" class="dropdown-item">Заметка</router-link>
                    </div>
                </li> -->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" title="Очистка" data-toggle="dropdown"><i class="fa fa-recycle"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a :href="url('app_common/clearcache')" class="dropdown-item">Полная очистка кэша</a>
                        <a :href="url('app_common/clearviews')" class="dropdown-item">Очистка шаблонов</a>
                        <a :href="url('app_common/optimize')" class="dropdown-item">Комплексная оптимизация</a>
                    </div>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'templates'}" class="nav-link" title="Редактор шаблонов"><i class="fa fa-paint-brush"></i></router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'system.settings'}" class="nav-link" title="Настройки системы"><i class="fa fa-cogs"></i></router-link>
                </li>
                <li class="nav-item"><a href="https://github.com/russsiq/bixbite" target="_blank" class="nav-link" title="Справка по системе"><i class="fa fa-leanpub"></i></a></li>
                <li class="nav-item">
                    <a href="#" class="nav-link" @click.prevent="showModal">
                        <img :src="currentUser.avatar" :alt="currentUser.name" class="rounded-circle" width="20" height="20" />
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Modal navbar #user_menu -->
    <modal v-if="modalShown" @close="closeModal" class="user__menu" :size="'small'">
        <template slot="modal__body">
            <div class="user-header"><img :src="skin('images/background_profile.jpg')"></div>
            <div class="user-avatar"><img :src="currentUser.avatar" class="rounded-circle" alt="User Image"></div>
            <div class="user-body"><b>{{ currentUser.name }}</b><br><small class="text-muted">{{ currentUser.role | trans }}</small></div>
        </template>

        <template slot="modal__footer">
            <router-link :to="{name: 'user.edit', params: {id: currentUser.id}}" class="btn btn-outline-success" @click.native="closeModal">Профиль</router-link>
            <button type="button" class="btn btn-secondary ml-auto" @click="logout">Выход</button>
        </template>
    </modal>
</header>
</template>

<script type="text/ecmascript-6">
import Modal from 'bxb-modal';
// import Article from '@/store/models/article';

import {
    mapGetters,
    mapActions
} from 'vuex'

export default {
    name: 'page-header',

    components: {
        'modal': Modal,
    },

    props: {
        //
    },

    data() {
        return {
            modalShown: false,
        }
    },

    computed: {
        ...mapGetters({
            currentUser: 'auth/user',
        }),
    },

    /**
     * При использовании хука `mounted`
     * идет пересчет вычисляемых полей `url(...)` из миксина.
     * Из-за переопределения локального свойства,
     * прописанного, например, в `data`.
     * Использование async/await не спасет ситуацию.
     */
    created() {
        // this.unpublished = Article.$fetch({
        //     query: {
        //         'f[0][column]': 'state',
        //         'f[0][operator]': 'not_equal_to',
        //         'f[0][query_1]': 'published',
        //         order_column: 'id',
        //         order_direction: 'desc',
        //         filter_match: 'and',
        //         limit: 1
        //     }
        // });
    },

    methods: {
        ...mapActions({
            signOut: 'auth/logout',
        }),

        logout() {
            this.signOut()
                .then(() => {
                    this.closeModal();
                    this.$router.push('/login');
                });
        },

        showModal() {
            this.modalShown = true;
        },

        closeModal() {
            this.modalShown = false;
        },
    },
}
</script>

<style>
.user__menu {
    min-width: 280px;
    position: absolute;
    right: 0;
    left: auto;
    padding: 0;
    border-radius: 0;
    text-align: center;
    background-color: #fff;
}

.user__menu .user-header {
    z-index: 5;
    height: 100px;
    background-color: rgba(41, 58, 72, 0.69);
    margin: -1rem;
    overflow: hidden;
}

.user__menu .user-header>img {
    width: 100%;
}

.user__menu .user-avatar {
    z-index: 8;
    margin: -40px auto 0;
    height: 80px;
    width: 80px;
    border-radius: 50%;
    overflow: hidden;
}

.user__menu .user-avatar>img {
    background: #fff;
    height: 80px;
    width: 80px;
    border: 5px solid transparent;
}

header .modal__footer {
    background-color: #f9f9f9;
    padding: 10px;
    border-top: 1px solid #e8e8e8;
}
</style>
