import axios from 'axios';
import store from '../store';

import AxiosConfig from './axios-config';

export default class Axios {
    constructor() {
        this.instance = axios.create(AxiosConfig);

        this.setInterceptors();

        return this.instance;
    }

    /**
     * Установить перехватчики на экземпляр библиотеки `axios`.
     */
    setInterceptors() {
        this.instance.interceptors.request.use(
            config => this.onRequest(config),
            error => this.onError(error),
        );

        this.instance.interceptors.response.use(
            response => this.onResponse(response),
            error => this.onError(error),
        );
    }

    /**
     * Default onRequest
     * @param {object} config
     * @param {Axios} Axios instance
     */
    onRequest(config) {
        store.dispatch('loadingLayer/show');

        return config;
    }

    /**
     * Обработка успешно выполненного запроса.
     * @param {object} response
     */
    onResponse(response) {
        store.dispatch('loadingLayer/hide');

        const statuses = {
            200: this.onSuccessResponse,
            201: this.onCreatedResponse,
            202: this.onUpdatedResponse,
            204: this.onDeletedResponse,
            206: this.onPartialContent,
        };

        const {
            data,
            status
        } = response;

        // Обязательно сохраняем контекст.
        // Так проще складировать набор методов.
        statuses[status] && statuses[status](response);

        // Возвращаем данные для хранилища `vuex-orm`.
        // В Laravel всё, включая  постраничку, оборачиваем в `data`.
        return data.data || data;
    }

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
    }

    /**
     * On 200 OK
     * @param {object} response
     */
    onSuccessResponse(response) {
        const message = response.data.message;

        message && Notification.success({
            message,
        });

        // Убираем подсветку невалидных полей.
        const form = response.config.targetForm;

        form && [...form.elements].forEach(function(element) {
            element.name && element.classList.toggle('is-invalid', false);
        });
    }

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
    }

    /**
     * On 202 Accepted
     * @param {object} response
     */
    onUpdatedResponse(response) {
        Notification.success({
            message: 'Обновлено.',
        });
    }

    /**
     * On 204 No Content
     * @param {object} response
     */
    onDeletedResponse(response) {
        Notification.success({
            message: 'Удалено.',
        });
    }

    /**
     * On 206 Partial Content
     * @param {object} response
     */
    onPartialContent(response) {
        //
    }

    /**
     * On Generic Error
     * @param {object} error
     */
    onGenericError(error) {
        Notification.error({
            message: error.message
        });
    }

    /**
     * On 401 Unauthorised.
     * @param {object} error
     */
    onUnauthorised(error) {
        Notification.error({
            title: '401. Вы не авторизованы',
            message: 'Вам необходимо авторизоваться для просмотра данной страницы или выполнения данной операции.',
        });
    }

    /**
     * On 403 Forbidden
     * @param {object} error
     */
    onForbidden(error) {
        Notification.error({
            title: '403. Запрещено',
            message: 'У вас ограничен доступ к просмотру данной страницы или выполнению данной операции.',
        });
    }

    /**
     * On 404 Not Found
     * @param {object} error
     */
    onNotFound(error) {
        Notification.error({
            title: '404. Страница не найдена',
            message: 'Извините, но запрашиваемая вами страница не найдена.',
        });
    }

    /**
     * On 419 Authentication timeout
     * @param {object} error
     */
    onAuthTimeout(error) {
        Notification.error({
            title: '419. Страница просрочена',
            message: 'Срок действия страницы истек из-за неактивности. Пожалуйста, обновите страницу и повторите попытку.'
        });
    }

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

        // Выполняем подсветку невалидных полей.
        const form = error.config.targetForm;

        form && [...form.elements].forEach(function(element) {
            element.name && element.classList.toggle('is-invalid',
                element.name in errors
            );
        });
    }

    /**
     * On 429 Too Many Requests
     * @param {object} error
     */
    onTooManyRequests(error) {
        Notification.error({
            title: '429. Выполнено слишком много запросов',
            message: 'Пожалуйста, повторите попытку позже.'
        });
    }

    /**
     * On 500 Server Error
     * @param {object} error
     */
    onServerError(error) {
        Notification.error({
            title: '500. Внутренняя ошибка сервера',
            message: 'Пожалуйста, повторите попытку позже.'
        });
    }

    /**
     * On 503 Service Unavailable
     * @param {object} error
     */
    onServiceUnavailable(error) {
        Notification.error({
            title: '503. Сервис временно недоступен',
            message: 'Пожалуйста, повторите попытку позже.'
        });
    }
}
