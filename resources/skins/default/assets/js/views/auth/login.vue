<template>
<div class="row">
    <link :href="skin('css/login.css')" rel="stylesheet" type="text/css" />

    <div class="login__box col-sm-offset-4 col-sm-4 col-xs-offset-2 col-xs-8">
        <h2 class="login__title">
            <a :href="url('/')" target="_blank">{{ app_name }}</a>
        </h2>
        <hr>
        <form v-if="!isLogged" @submit.prevent="signIn" class="form-horizontal" @input="resetErrors">
            <fieldset>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="email" v-model="form.email" class="form-control" placeholder="Email" required />
                    </div>
                    <small v-for="error in errors.email" class="error__control" v-html="error"></small>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" v-model="form.password" class="form-control" placeholder="Password" required />
                    </div>
                    <small v-for="error in errors.password" class="error__control">{{ error }}</small>
                </div>
                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-outline-secondary pull-right" :disabled="isProcessing">Войти</button>
                </div>
                <p class="copyright">2018-2019 © <a href="https://github.com/russsiq/bixbite" target="_blank">BixBite CMS</a></p>
            </fieldset>
        </form>
    </div>
</div>
</template>

<script type="text/ecmascript-6">
import {
    mapActions,
    mapGetters
} from 'vuex'

export default {
    name: 'login',

    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            errors: {},
            isProcessing: false,

            title: 'Авторизуйтесь, пожалуйста.',
            message: 'Для работы с панелью необходимо быть зарегистрированным и аутентифицированным пользователем.<hr>Для доступа к определенным разделам необходимо иметь соответствующий статус.'
        }
    },

    computed: {
        ...mapGetters({
            isLogged: 'auth/isLogged'
        })
    },

    created() {
        this.$notification.info({
            title: this.title,
            message: this.message
        });

        // Если пользователь сразу зашел на страницу авторизации,
        // например, набрал адрес в браузере, но есть вероятность,
        // что он авторизован, то его необходимо перенаправить.
        this.authInitialize()
            .then(() => {
                this.isLogged && this.$router.push('/');
            })
            .catch(error => {
                console.error(error);
            });
    },

    methods: {
        ...mapActions({
            authLogin: 'auth/login',
            authInitialize: 'auth/initialize',
        }),

        signIn() {
            this.isProcessing = true;
            this.errors = {};

            this.authLogin(this.form)
                .then((user) => {
                    window.location.href = this.$router.options.base;
                })
                .catch((error) => {
                    this.isProcessing = false;

                    if (error.response && [422, 429].includes(error.response.status)) {
                        this.errors = error.response.data.errors;
                    }
                });
        },

        resetErrors(event) {
            this.errors = {};
        }
    }
}
</script>
