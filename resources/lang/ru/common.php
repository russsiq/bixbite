<?php

return [
    'yes' => 'Да',
    'no' => 'Нет',
    'loading' => 'Пожалуйста, подождите . . .',
    'read_more' => 'Подробнее ...',
    'powered_by' => 'Powered by <a href="https://github.com/russsiq/bixbite" target="_blank" class="page_footer__link">BixBite</a>.',

    'home' => 'Главная страница',
    'search' => 'Поиск по сайту',
    'query' => 'Введите запрос ...',
    'articles' => 'Записи',
    'article' => 'Запись',
    'categories' => 'Категории',
    'category' => 'Категория',
    'comments' => 'Комментарии',
    'comment' => 'Комментарий',
    'tags' => 'Теги',
    'tag' => 'Тег',

    // Buttons
    'btn' => [
        'save' => 'Сохранить',
        'edit' => 'Редактировать',
        'delete' => 'Удалить',
        'search' => 'Найти',
        'cancel' => 'Отмена',
        'continue' => 'Продолжить &raquo;',
        'prev' => '&laquo; Назад',
        'next' => 'Далее &raquo;',
        'finish' => 'Завершить',
    ],

    // Messages
    'msg' => [
        'sure' => 'Вы уверены?',
        'sure_del' => 'Хотите удалить?',
        'complete' => 'Успешно выполнено.',
        'cache_clear' => 'Кэш успешно очищен.',
        'error' => '<pre><font color="red">%s</font></pre>',
        'not_found' => '<p class="alert alert-info text-center">Нет информации для отображения.</p>',
        'env_fails' => 'Не удалось прочесть `.env` файл! Проверьте его на наличие ошибок.',
    ],

    // Errors
    'error' => [
        401 => [
            'title' => '401. Вы не авторизованы',
            'message' => 'Вам необходимо авторизоваться для просмотра данной страницы или выполнения данной операции.',
        ],
        403 => [
            'title' => '403. Запрещено',
            'message' => 'У вас ограничен доступ к просмотру данной страницы или выполнению данной операции.',
        ],
        404 => [
            'title' => '404. Страница не найдена',
            'message' => 'Извините, но запрашиваемая вами страница не найдена.',
        ],
        419 => [
            'title' => '419. Страница просрочена',
            'message' => 'Срок действия страницы истек из-за неактивности. Пожалуйста, обновите страницу и повторите попытку.',
        ],
        429 => [
            'title' => '429. Выполнено слишком много запросов.',
            'message' => 'Пожалуйста, повторите попытку позже.',
        ],
        500 => [
            'title' => '500. Внутренняя ошибка сервера',
            'message' => 'Пожалуйста, повторите попытку позже.',
        ],
        503 => [
            'title' => '503. Сервис временно недоступен',
            'message' => 'Пожалуйста, повторите попытку позже.',
        ],
    ],

    'bytes' => 'байт',
    'KB' => 'КБ',
    'MB' => 'МБ',
    'GB' => 'ГБ',
    'TB' => 'ТБ',
];
