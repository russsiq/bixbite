import Vue from 'vue';
import {
    mapGetters
} from 'vuex';

import Setting from '@/store/models/setting';

export default Vue.extend({
    data() {
        return {
            form: {},
            // Для наследуемых модулей
            // является обязательным.
            // entity: 'articles',
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
        }),

        showedForm() {
            return Object.keys(this.form).length > 0;
        },
    },

    mounted() {
        // Обманным путем загружаем настройки только для текущего модуля.
        // Будет выполнен запрос по адресу, например:
        // GET: `/api/v1/settings/articles`.
        Setting.$get({
                params: {
                    id: this.entity
                }
            })
            .then(this.fillForm);
    },

    methods: {
        /**
         * Заполнить форму с настройками.
         * @param  {Array}  data  Массив с настройками.
         */
        fillForm(data) {
            data.map(this.addField);
        },

        /**
         * Добавить поле для настройки в форму.
         * @param  {Setting}  setting
         */
        addField(setting) {
            this.form = Object.assign({}, this.form, {
                [setting.name]: setting.value,
            });
        },

        /**
         * Обработать отправку формы.
         * @param  {Event}  event
         */
        onSubmit(event) {
            const form = event.target;

            this.hignlightInvalidInput(form);

            // Если форма провалидирована, то сохраняем настройки.
            // @NB: `form.checkValidity()` – не предотвращает отправку формы.
            form.checkValidity() && Setting.$update({
                params: {
                    id: this.entity
                },

                data: {
                    ...this.form
                }
            });
        },

        /**
         * Установить фокус на первом невалидном поле ввода.
         * @param  {HTMLFormElement}  form
         */
        hignlightInvalidInput(form) {
            // Перебираем элементы формы.
            const elements = [...form.elements];

            elements.forEach(element => {
                // Принудительно расставляем атрибут `required`.
                // Все поля должны попадать в БД со значениями.
                // Исключения составляют:
                //  - `checkbox` с булевыми значениями;
                //  - поля с атрибутом `disabled`;
                'checkbox' !== element.type &&
                    // !element.getAttribute('readonly') &&
                    !element.getAttribute('disabled') &&
                    element.setAttribute('required', 'required');

                // Валидируем поле.
                const isValid = element.validity && element.validity.valid;

                // Добавляем класс, если поле не валидно.
                element.classList.toggle('is-invalid', !isValid);
            });

            // Ищем первое невалидное поле.
            const first = elements.find(element => element.classList.contains('is-invalid'));

            // Выходим, если все поля валидны по мнению браузера.
            if (!first) return true;

            // Далее необходимо переключить вкладку
            const pane = first.closest('.tab-pane');
            const tab = form.querySelector(`[href="#${pane.id}"]`);

            // Просто кликаем по вкладке.
            // @NB: не во всех формах могут быть вкладки.
            pane && tab && tab.click();

            // Устанавливаем фокус на первое невалидное поле.
            first.focus();

            // Отображаем подсказку невалидности. Пусть будет оранжевая,
            // так как в данный момент ничего критичного не случилось.
            first.validationMessage && this.$notification.warning({
                message: first.validationMessage,
            });
        }
    },
});
