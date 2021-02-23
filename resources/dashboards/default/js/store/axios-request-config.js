/**
 * Axios Config
 */

import Axios from 'axios';
import { Bus } from '@/store/bus.js';
import Router from '@/router';
import Toast from '@/helpers/notification';

const http = {
    /**
     * Default URL.
     */
    url: '/',

    /**
     * Default base URL.
     */
    baseURL: `${BixBite.api_url}/`,

    /**
     * Default method.
     */
    method: 'get',

    /**
     * Default Headers.
     */
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },

    /**
     * Default onRequest.
     *
     * @param {object} config
     * @param {Axios} Axios instance
     */
    onRequest(config, axios) {
        Bus.$emit('spinner.show');

        return config;
    },

    /**
     * Handle successful response.
     *
     * @param {object} response
     */
    onResponse(response) {
        Bus.$emit('spinner.hide');

        const statuses = {
            201: this.onCreatedResponse,
            202: this.onUpdatedResponse,
            204: this.onDeletedResponse,
            206: this.onPartialContent,
        };

        const {
            data,
            status
        } = response;

        // Устанавливаем мета-данные.
        data && this.setMetaData(data.meta);

        // Обязательно сохраняем контекст.
        // Так проще складировать набор методов.
        status in statuses && statuses[status].apply(this, arguments);

        // Возвращаем данные. В Laravel всё,
        // включая  постраничку, оборачиваем в `data`.
        return data && data.data;
    },

    /**
     * On 201 Created.
     *
     * @param {object} response
     */
    onCreatedResponse(response) {
        Toast.success({
            message: response.data.message || 'Created.',
        });

        // Перенаправляем на страницу редактирования.
        this.redirectToEdit(response.data.data.id, response.config.url);
    },

    /**
     * On 202 Accepted.
     *
     * @param {object} response
     */
    onUpdatedResponse(response) {
        Toast.success({
            message: 'Updated.',
        });
    },

    /**
     * On 204 No Content.
     *
     * @param {object} response
     */
    onDeletedResponse(response) {
        Toast.success({
            message: 'Deleted.',
        });
    },

    /**
     * On 206 Partial Content.
     *
     * @param {object} response
     * @NB: При программном изменении маршрута и
     *      дублировании возникает ошибка: `NavigationDuplicated`.
     *      https://github.com/vuejs/vue-router/issues/2881
     *      Временное лечение: перехватить ошибку обработчиком onAbort.
     *      Перепроверить поведение на `Router.replace`.
     */
    onPartialContent(response) {
        const meta = response.data.meta;

        // Если переданы метаданные для постранички.
        // Нужно что-то придумать как это дело отменить:
        // т.е. на главной странице панели не должно быть постранички.
        if (meta && meta.current_page) {
            Bus.$emit('paginator.paginate', meta);

            Router.push({
                    query: {
                        // Сохраняем параметры текущей страницы.
                        ...Router.currentRoute.query,
                        page: parseInt(meta.current_page, 10)
                    }
                },
                function onComplete(route) {},
                function onAbort(error) {}
            );
        } else {
            Bus.$emit('paginator.reset');
        }
    },

    /**
     * Default on Error.
     *
     * @param {object} error
     */
    onError(error) {
        Bus.$emit('spinner.hide');

        const statuses = {
            401: this.onUnauthorised,
            403: this.onForbidden,
            404: this.onNotFound,
            419: this.onAuthTimeout,
            422: this.onValidationError,
            429: this.onTooManyRequests,
            500: this.onServerError,
            503: this.onServiceUnavailable,
        };

        const response = error.response;

        if (response && statuses[response.status]) {
            statuses[response.status](error);
        } else {
            this.onGenericError(error);
        }

        return Promise.reject(error);
    },

    /**
     * On Generic Error.
     *
     * @param {object} error
     */
    onGenericError(error) {
        Toast.error({
            message: error.message
        });
    },

    /**
     * On 401 Unauthorised.
     *
     * @param {object} error
     */
    onUnauthorised(error) {
        Router.replace('unauthorized');
    },

    /**
     * On 403 Forbidden.
     *
     * @param {object} error
     */
    onForbidden(error) {
        Router.replace('forbidden');
    },

    /**
     * On 404 Not Found.
     *
     * @param {object} error
     */
    onNotFound(error) {
        Router.replace('not-found');
    },

    /**
     * On 419 Authentication timeout.
     * On 419 Page Expired.
     *
     * @param {object} error
     */
    onAuthTimeout(error) {
        Toast.error({
            title: '419. Authentication timeout',
            message: 'Срок действия страницы истек из-за неактивности. Пожалуйста, обновите страницу и повторите попытку.'
        });
    },

    /**
     * On Laravel Validation Error (Or 422 Error).
     *
     * @param {object} error
     */
    onValidationError(error) {
        const errors = error.response.data.errors;

        for (const field in errors) {
            errors[field].forEach(function(message) {
                Toast.warning({
                    message: message
                });
            });
        }
    },

    /**
     * On 429 Too Many Requests.
     *
     * @param {object} error
     */
    onTooManyRequests(error) {
        Toast.error({
            title: '429. Too Many Requests',
            message: 'Пожалуйста, повторите попытку позже.'
        });
    },

    /**
     * On 500 Server Error.
     *
     * @param {object} error
     */
    onServerError(error) {
        Toast.error({
            title: '500. Server Error',
            message: 'Пожалуйста, повторите попытку позже.'
        });
    },

    /**
     * On 503 Service Unavailable.
     *
     * @param {object} error
     */
    onServiceUnavailable(error) {
        Toast.error({
            title: '503. Service Unavailable',
            message: 'Пожалуйста, повторите попытку позже.'
        });
    },

    /**
     * Установить мета-данные в хранилище,
     * если они были переданы с ответов с сервера.
     * @NB: Нельзя сбрасывать все мета-данные!
     *      При этом как-то нужно учитывать
     *      нелигальное переписывание мета-данных
     *      одних сущностей мета-данными других сущностей.
     *
     * @param {object} meta Объект с мета-данными.
     */
    setMetaData(meta) {
        Bus.$emit('meta.set', meta);

        // !meta && store.dispatch('meta/reset');
    },

    /**
     * Перенаправить на страницу редактирования после создания сущности.
     *
     * @param  {integer} id Идентификатор созданной сущности.
     * @param  {string} requestedUrl Адрес, на который был выполнен запрос.
     */
    redirectToEdit(id, requestedUrl) {
        const name = Router.currentRoute.name.split('.').shift();

        // Перенаправление на редактирование сущности,
        // если текущий маршрут совпадает с запрашиваемым ресурсом.
        requestedUrl.endsWith(name) && Router.push({
            path: `/${name}/${id}/edit`
        });
    },
};

const instance = Axios.create(http);

instance.interceptors.request.use(
    config => http.onRequest(config, instance),
    error => http.onError(error, instance),
);

instance.interceptors.response.use(
    response => http.onResponse(response, instance),
    error => http.onError(error, instance),
);

export default instance;
