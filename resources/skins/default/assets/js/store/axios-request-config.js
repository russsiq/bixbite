/**
 * Axios Config
 */

import store from '@/store';
import router from '@/router';

export default {
    /**
     * Default create new axios instance, provide
     * option to pass an existing instance through.
     * @NB: Невозможно использовать из-за
     *      повторного отправления `onResponse`, подробнее:
     *      https://github.com/vuex-orm/plugin-axios/issues/39
     */
    // axios: axios.create(http),

    /**
     * Default URL.
     */
    url: '/',

    /**
     * Default base URL.
     */
    baseURL: `${Pageinfo.api_url}/`,

    /**
     * Default method.
     */
    method: 'get',

    /**
     * Default Headers
     */
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': `${Pageinfo.csrf_token}`,
        'X-Requested-With': 'XMLHttpRequest',
    },

    /**
     * Access token variable.
     * Sending header: 'Authorization': 'Bearer access_token',
     */
    access_token() {
        return store.getters['auth/api_token'];
    },

    /**
     * Default onRequest
     * @param {object} config
     * @param {Axios} Axios instance
     */
    onRequest(config, axios) {
        store.dispatch('loadingLayer/show');

        return config;
    },

    /**
     * Обработка успешно выполненного запроса.
     * @param {object} response
     */
    onResponse(response) {
        store.dispatch('loadingLayer/hide');

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

        // Возвращаем данные для хранилища `vuex-orm`.
        // В Laravel всё, включая  постраничку, оборачиваем в `data`.
        return data && data.data;
    },

    /**
     * On 201 Created
     * При загрузке файла лучше выглядит сообщение о том,
     * что файл загружен, а не просто `Создано`.
     * @param {object} response
     */
    onCreatedResponse(response) {
        Notification.success({
            message: response.data.message || 'Создано.',
        });

        // Перенаправляем на страницу редактирования.
        this.redirectToEdit(response.data.data.id, response.config.url);
    },

    /**
     * On 202 Accepted
     * @param {object} response
     */
    onUpdatedResponse(response) {
        Notification.success({
            message: 'Обновлено.',
        });
    },

    /**
     * On 204 No Content
     * @param {object} response
     */
    onDeletedResponse(response) {
        Notification.success({
            message: 'Удалено.',
        });
    },

    /**
     * On 206 Partial Content
     * @param {object} response
     * @NB: При программном изменении маршрута и
     *      дублировании возникает ошибка: `NavigationDuplicated`.
     *      https://github.com/vuejs/vue-router/issues/2881
     *      Временное лечение: перехватить ошибку обработчиком onAbort.
     *      Перепроверить поведение на `router.replace`.
     */
    onPartialContent(response) {
        const meta = response.data.meta;

        // Если переданы метаданные для постранички.
        // Нужно что-то придумать как это дело отменить:
        // т.е. на главной странице панели не должно быть постранички.
        if (meta && meta.current_page) {
            store.dispatch('paginator/paginate', meta);

            router.push({
                    query: {
                        // Сохраняем параметры текущей страницы.
                        ...router.currentRoute.query,
                        page: parseInt(meta.current_page, 10)
                    }
                },
                function onComplete(route) {},
                function onAbort(error) {}
            );
        } else {
            store.dispatch('paginator/resetPaginator');
        }
    },

    /**
     * Default on Error
     * @param {object} error
     */
    onError(error) {
        store.dispatch('loadingLayer/hide');

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
     * On Generic Error
     * @param {object} error
     */
    onGenericError(error) {
        Notification.error({
            message: error.message
        });
    },

    /**
     * On 401 Unauthorised.
     * @param {object} error
     */
    onUnauthorised(error) {
        // Сбрасываем аутентификацию пользователя.
        store.dispatch('auth/logout');

        // Перенапрвляем на страницу регистрации.
        router.push({
            name: 'login'
        });
    },

    /**
     * On 403 Forbidden
     * @param {object} error
     */
    onForbidden(error) {
        Notification.error({
            title: '403. Запрещено',
            message: 'У вас ограничен доступ к просмотру данной страницы или выполнению данной операции.',
        });
    },

    /**
     * On 404 Not Found
     * @param {object} error
     */
    onNotFound(error) {
        router.replace('not-found');
    },

    /**
     * On 419 Authentication timeout
     * @param {object} error
     */
    onAuthTimeout(error) {
        Notification.error({
            title: '419. Страница просрочена',
            message: 'Срок действия страницы истек из-за неактивности. Пожалуйста, обновите страницу и повторите попытку.'
        });
    },

    /**
     * On Laravel Validation Error (Or 422 Error).
     * Также используется в загрузчике изображений.
     * @param {object} error
     */
    onValidationError(error) {
        const errors = error.response.data.errors;

        for (let field in errors) {
            errors[field].forEach(function(message) {
                Notification.warning({
                    message: message
                });
            });
        }
    },

    /**
     * On 429 Too Many Requests
     * @param {object} error
     */
    onTooManyRequests(error) {
        Notification.error({
            title: '429. Выполнено слишком много запросов',
            message: 'Пожалуйста, повторите попытку позже.'
        });
    },

    /**
     * On 500 Server Error
     * @param {object} error
     */
    onServerError(error) {
        Notification.error({
            title: '500. Внутренняя ошибка сервера',
            message: 'Пожалуйста, повторите попытку позже.'
        });
    },

    /**
     * On 503 Service Unavailable
     * @param {object} error
     */
    onServiceUnavailable(error) {
        Notification.error({
            title: '503. Сервис временно недоступен',
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
     * @param {object} meta Объект с мета-данными.
     */
    setMetaData(meta) {
        meta && store.dispatch('meta/set', meta);
        // !meta && store.dispatch('meta/reset');
    },

    /**
     * Перенаправить на страницу редактирования после создания сущности.
     * @param  {integer} id Идентификатор созданной сущности.
     * @param  {string} requestedUrl Адрес, на который был выполнен запрос.
     */
    redirectToEdit(id, requestedUrl) {
        const name = router.currentRoute.name.split('.').shift();

        // Перенаправление на редактирование сущности,
        // если текущий маршрут совпадает с запрашиваемым ресурсом.
        requestedUrl.endsWith(name) && router.push({
            path: `/${name}/${id}/edit`
        });
    },
};
