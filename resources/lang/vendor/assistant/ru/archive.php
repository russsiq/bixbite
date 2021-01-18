<?php

return [
    'headers' => [
        'welcome' => 'Приветствие',
        'complete' => 'Выполнено',

    ],

    'descriptions' => [
        'welcome' => '<p>Данный мастер поможет в управлении резервными копиями приложения.</p><p>Выберите необходимые опции и нажмите <kbd>Далее</kbd>.</p>',
        'complete' => '<p>Завершена работа по выбранным вами опциям:</p>',

    ],

    'strings' => [

    ],

    'forms' => [
        'legends' => [
            'backup' => 'Создать резервную копию',
            'restore' => 'Восстановление из архива',

        ],

        'attributes' => [
            'filename' => 'Файл резервной копии',
            'backup_database' => 'Дамп базы данных',
            'backup_system' => 'Системные файлы',
            'backup_theme' => 'Активная тема',
            'backup_uploads' => 'Вложенные файлы',

            'restore_database' => 'Дамп базы данных',
            'restore_system' => 'Системные файлы',
            'restore_theme' => 'Активная тема',
            'restore_uploads' => 'Вложенные файлы',

        ],

        'labels' => [
            'filename' => 'Список файлов резервных копий',
            'backup_database' => 'Дамп базы данных',
            'backup_system' => 'Системные файлы',
            'backup_theme' => 'Активная тема',
            'backup_uploads' => 'Вложенные файлы',

            'restore_database' => 'Дамп базы данных',
            'restore_system' => 'Системные файлы',
            'restore_theme' => 'Активная тема',
            'restore_uploads' => 'Вложенные файлы',

        ],

        'placeholders' => [

        ],

        'validation' => [
            'restore.required_without' => 'Необходимо выбрать хотя бы одну опцию.'
        ],
    ],

    'messages' => [
        'errors' => [

        ],

    ],

];
