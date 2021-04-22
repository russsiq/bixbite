// NEED TO REFRESH TOKKEN

import {
    post
} from '@/helpers/api';

const AUTH_INITIALIZE = 'AUTH_INITIALIZE';
const AUTH_REQUEST = 'AUTH_REQUEST';
const AUTH_SUCCESS = 'AUTH_SUCCESS';
const AUTH_ERROR = 'AUTH_ERROR';
const AUTH_RESET = 'AUTH_RESET';

const BASE_URL = `${Pageinfo.api_url}/auth/`;
const ALLOWED_ATTRIBUTE = [
    'id',
    'name',
    'avatar',
    'role',
    'api_token',
];

export default {
    namespaced: true,

    state: {
        status: null,
        user: null,
        base_url: BASE_URL,
    },

    getters: {
        user: state => state.user,
        api_token: state => state.user && state.user.api_token,
        authStatus: state => state.status,
        isLogged: state => Boolean(state.user && state.user.api_token),
    },

    mutations: {
        [AUTH_INITIALIZE](state, user) {
            state.status = 'initialized';
            state.user = user;
        },

        [AUTH_REQUEST](state) {
            state.status = 'loading';
            state.user = null;
        },

        [AUTH_SUCCESS](state, user) {
            state.status = 'success';
            state.user = user;
        },

        [AUTH_ERROR](state) {
            state.status = 'error';
            state.user = null;
        },

        [AUTH_RESET](state) {
            state.status = null;
            state.user = null;
        },
    },

    actions: {
        _reset(context) {
            localStorage.removeItem('user');

            context.commit(AUTH_RESET);
        },

        /**
         * Инициализация авторизации пользователя.
         * Выполняется единожды при создании экземпляра приложения.
         * @NB: НЕ нужно сохранять данные о пользователе в localStorage, только api_token.
         *      При инициализации приложения делать запрос и хранить данные в памяти???
         * @param  {[type]} context [description]
         * @return {[type]}         [description]
         */
        initialize(context) {
            return new Promise(function(resolve, reject) {
                try {
                    context.commit(
                        AUTH_INITIALIZE,
                        JSON.parse(localStorage.getItem('user'))
                    );

                    resolve();
                } catch (error) {
                    context.dispatch('_reset');
                    context.commit(AUTH_ERROR);

                    reject(error.message);
                }
            })
        },

        logout(context) {
            return new Promise(function(resolve, reject) {
                context.dispatch('_reset');

                resolve();
            });
        },

        login(context, credentials) {
            context.dispatch('_reset');
            context.commit(AUTH_REQUEST);

            return post(context.state.base_url + 'login', credentials)
                .then(response => {

                    // Когда пользователю успешно присваивается новый `api_token`,
                    // возвращается 202 статус.
                    if (202 != response.status) {
                        throw new Error(response.data.message);
                    }

                    const user = response.data.data;
                    user.api_token = response.headers.api_token;

                    for (let attribute in user) {
                        // Запрещенные свойства будут удалены и в `response.data.user`.
                        if (!ALLOWED_ATTRIBUTE.includes(attribute)) {
                            delete user[attribute];
                        }
                    }

                    localStorage.setItem('user', JSON.stringify(user));

                    context.commit(AUTH_SUCCESS, user);

                    return user;
                })
                .catch(error => {
                    context.commit(AUTH_ERROR);

                    return Promise.reject(error);
                    // throw error;
                });
        },
    },
};
