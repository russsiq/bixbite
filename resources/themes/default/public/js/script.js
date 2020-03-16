/**
 * Персональные скрипты темы.
 */

'use strict';

// Индентификаторы элементов пользовательского интерфейса.
const UI_MAP = {
    img_captcha: '#img_captcha',
    comments: '#comments',
}

// Список форм, для которых имеются обработчики.
const FORMS_MAP = {
    respond_form: respondFormDataHandler,
    feedback_form: feedbackFormDataHandler,
}

// Список форм, по атрибуту `action` которых может быть определен обработчик.
// В данном шаблоне темы сайта используется временно. Указан в качестве примера.
// Для комментариев перехватывает редактирование/удаление.
const FORMS_ACTION_MAP = {
    '/comments/([0-9]+)$': commentInterceptor,
}

// Чтобы постоянно не пересчитывать ключи для регулярных выражений карты форм.
FORMS_ACTION_MAP.keys = Object.keys(FORMS_ACTION_MAP);
FORMS_ACTION_MAP.first = function(action) {
    return FORMS_ACTION_MAP.keys.find((key) => (new RegExp(key)).exec(action));
}

// Список переключателей для изменения отображения
// интерактивных элементов интерфейса.
const TOGGLES_MAP = {
    tab: tabHandler,
    dropdown: dropdownHandler,
    collapse: collapseHandler
}

// Определяем дополнительных обработчиков после полной загрузки DOM.
document.addEventListener('DOMContentLoaded', function() {
    // // Создаем экземпляр AJAX провайдера.
    // const axios = new Axios;
    //
    // // Отправляем AJAX запрос.
    // axios.get('http://localhost/bixbite/widget/articles.featured', {
    //     params:{
    //         active: true
    //     }
    // })
    // .then((response) => {
    //     console.log(response);
    // });

    // Распределяем обработчиков отправки форм в менеджере форм.
    this.addEventListener('submit', sendFormManager);

    // Распределяем обработчиков переключателей.
    this.addEventListener('click', clickHandler);

    // Если на странице есть стандартная капча, то добавляем обработчика события.
    const img_captcha = this.querySelector(UI_MAP.img_captcha);
    img_captcha && img_captcha.addEventListener('click', reloadCaptcha);

    // Добавляем обработчика для кнопок `Ответить`, `Отменить` в форме комментариев.
    const comments = this.querySelector(UI_MAP.comments);
    comments && comments.addEventListener('click', replyClickHandler);
});

/**
 * Менеджер отправки данных форм по AJAX.
 * @param  {Event} event Объект события `submit`.
 * @return {void}
 */
function sendFormManager(event) {
    // Текущая форма HTMLFormElement.
    const form = event.target;

    if (!(form instanceof HTMLFormElement)) {
        event.preventDefault();

        throw('Доступно только для форм.');
    }

    // Если текущая форма не имеет идентификатор.
    // Пытаемся привязаться к атрибуту `action`.
    // Например, отловить редактирование/удаление комментария.
    const action = form.action;

    // Если текущая форма имеет идентификатор, то берем обработчик из карты форм,
    // иначе пытаемся получить из карты форм для атрибута `action`.
    // Результат проверки регулярным выражением возвращает `false`,
    // если совпадения отсутствуют. После передачи обработчику данных,
    // результаты совпадения доступны ч/з экземпляр `RegExp['$1']`.
    const form_id = form.id || FORMS_ACTION_MAP.first(action);

    // Обработчик отправки текущей формы.
    const handler = form.id ? FORMS_MAP[form_id] : FORMS_ACTION_MAP[form_id];

    // Если для формы имеется обработчик.
    if (handler) {
        // Отменяем стандартную отправку формы.
        event.preventDefault();

        // Создаем экземпляр AJAX провайдера.
        const axios = new Axios;

        // Собираем данные с формы для отправки.
        const data = new FormData(form);

        // Передаем данные обработчикам.
        const promise = handler.apply(form, [axios, data, event]);

        // Если подключена GRecaptcha, то перезагружаем её.
        promise.finally(() => {
            if ('grecaptcha' in window) {
                grecaptcha_reload();
            } else if ('img_captcha' in window) {
                // Давайте пока просто кликнем.
                window['img_captcha'].click();
            }
        });
    }
}

/**
 * Отправка данных формы обратной связи по AJAX.
 * @param  {Axios} axios Экземляр AJAX провайдера.
 * @param  {FormData} data Экземляр объекта серилизованных данных формы.
 * @param  {Event} event Объект события `submit`.
 * @return {Promise}
 */
function feedbackFormDataHandler(axios, data, event) {
    // Отправляем AJAX запрос.
    return axios.post(this.action, data)
        .then((response) => {
            // В случае успешной отправки, очищаем форму.
            this.reset();
        });
}

/**
 * Перехватчик редактирования/удаления комментария.
 * Результаты совпадения при проверки регулярным выражением
 * доступны ч/з экземпляр `RegExp['$1']`. Но в данном случае
 * получим `id` комментария повторно из атрибута `action` формы.
 * @param  {Axios} axios Экземляр AJAX провайдера.
 * @param  {FormData} data Экземляр объекта серилизованных данных формы.
 * @param  {Event} event Объект события `submit`.
 * @return {Promise}
 */
function commentInterceptor(axios, data, event) {
    const id = this.action.split('/').pop();
    const comment = document.getElementById('comment-'+id);
    const _method = data.get('_method').toLowerCase();

    // Отправляем AJAX запрос.
    return axios.post(this.action, data)
        .then((response) => {
            // В случае успешной отправки:
            switch (_method) {
                // - выполняем редирект.
                case 'put':
                    // self.location = response.route;
                    break;

                // - удаляем комментарий из DOM.
                case 'delete':
                    comment && comment.remove();
                    break;

                default:
                    console.error('Неизвестное действие.');
            }
        });
}

/**
 * Отправка данных формы комментариев по AJAX.
 * @param  {Axios} axios Экземляр AJAX провайдера.
 * @param  {FormData} data Экземляр объекта серилизованных данных формы.
 * @param  {Event} event Объект события `submit`.
 * @return {void}
 */
function respondFormDataHandler(axios, data, event) {
    // Отправляем AJAX запрос, а также передаем форму
    // для подсветки невалидных полей.
    return axios.post(this.action, data, {
            targetForm: event.target
        })
        .then((response) => {
            // Сократим вложенность атрибутов комментария.
            const comment = response.comment;
            const parent_id = comment.parent_id;

            // В случае успешной отправки, очищаем форму.
            this.reset();

            // Очищаем скрытые поля формы.
            this.elements['parent_id'].value = '';

            // Программными кликами, создав событие на элементах,
            // перезагружаем капчу и возвращаем форму на место.
            this.img_captcha && this.img_captcha.click();
            parent_id && this.cancel_reply && this.cancel_reply.click();

            // Родительский узел для вставки комментария.
            // Зависит от того, имеет ли добавляемый комментарий родителя.
            // `lastElementChild` - дочерние комментарии должны быть
            // обернуты в один тег, расположенный последним в иерархии DOM.
            const parentNode = parent_id ?
                window['comment-' + parent_id].lastElementChild :
                window['comments_list'];

            // Добавляем к родительскому узлу созданный комментарий.
            // Ничего лучше не было придумано.
            const fragment = document.createElement('template');
            fragment.innerHTML = comment.html;
            parentNode.appendChild(fragment.content);

            // Программно прокручиваем страницу до вставленного комментария.
            window['comment-' + comment.id].scrollIntoView({
                block: 'center',
                behavior: 'smooth'
            });
        });
}


/**
 * Перезагрузка картинки капчи по клику.
 * @param  {Event} event Объект события `click`.
 * @return {void}
 */
function reloadCaptcha(event) {
    const source = this.dataset.src || this.src;
    this.src = source.replace(/(\d*)$/, Date.now);

    this.closest('form').elements['g-recaptcha-response'].value = '';
}

/**
 * Обработчик для кнопок `Ответить`, `Отменить` в форме комментариев.
 * @param  {Event} event Объект события `click`.
 * @return {void}
 */
function replyClickHandler(event) {
    // Элемент, по которому был выполнен щелчок.
    const target = event.target;

    // Первый ближайший родительский элемент (или сам элемент),
    // который содержит атрибут `data-reply`,.
    const element = target.closest('[data-reply]');

    // Если таковой элемент был найден.
    if (element) {
        // Отменяем стандартное поведение для клика по ссылке.
        event.preventDefault();

        // Отменяем всплытие клика.
        event.stopPropagation();

        // Идентификатор родительского комментария.
        const message_id = element.dataset.reply;

        // Родительский узел для вставки формы.
        // Зависит от того, имеет ли добавляемый комментарий родителя.
        const parentNode = message_id ?
            window['comment-' + message_id] :
            window['comments_list'].parentNode;

        // Перемещаем форму комментирования.
        parentNode.appendChild(window['respond']);

        // Устанавливаем для скрытого поля идентификатор родителя.
        window['respond_form'].elements['parent_id'].value = message_id;

        // Переключаем отображение кнопки `Отменить`.
        window['cancel_reply'].classList.toggle('d-none', !element.matches('.comment__reply'));

        // Программно прокручиваем страницу до блока с формой комментирования.
        window['respond'].scrollIntoView({
            block: 'center',
            behavior: 'smooth'
        });
    }
}

/**
 * Обработчик щелчков мышью.
 * @param  {Event} event Объект события `click`.
 * @return {void}
 */
function clickHandler(event) {
    // Элемент, по которому был выполнен щелчок.
    const target = event.target;

    // Первый ближайший родительский элемент (или сам элемент),
    // который содержит атрибут `data-toggle`.
    const element = target.closest('[data-toggle]');

    // Если таковой элемент был найден и
    // он имеет не пустой необходимый атрибут.
    if (element && element.dataset.toggle) {
        // Обработчик текущего щелчка.
        const handler = TOGGLES_MAP[element.dataset.toggle];

        if (handler) {
            // Отменяем стандартное поведение для клика по ссылке.
            event.preventDefault();

            // Отменяем всплытие клика.
            event.stopPropagation();

            // Передаем данные обработчику.
            handler.apply(element, event);
        }
    }
}

function tabHandler(event) {
    const selector = this.getAttribute('href') || this.dataset.target;
    const parent = this.closest('[role="tablist"]').parentNode;

    const [...tabs] = parent.querySelectorAll('[role="tab"]');
    const [...panes] = parent.querySelectorAll('[role="tabpanel"]');

    tabs.forEach(function(item) {
        const isActive = item.matches(`[href="${selector}"]`);

        item.classList.toggle('active', isActive);
        item.classList.toggle('show', isActive);
    });

    panes.forEach(function(item) {
        const isActive = item.matches(selector);

        item.classList.toggle('active', isActive);
        item.classList.toggle('show', isActive);
    });
}

function dropdownHandler(event) {
    const dropdown = this.nextElementSibling;

    if (dropdown) {
        dropdown.classList.toggle('show');

        this.addEventListener('blur', function(event) {
            dropdown.classList.toggle('show', false);
            event.relatedTarget && event.relatedTarget.click();
        }, {
            once: true,
        });
    }
}

function collapseHandler(event) {
    const selector = this.getAttribute('href') || this.dataset.target;
    const collapseble = document.body.querySelector(selector);

    collapseble && collapseble.classList.toggle('collapse');
}
