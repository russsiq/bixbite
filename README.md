<img src="https://raw.githubusercontent.com/russsiq/bixbite/master/public/favicon.ico">

BixBite – проект на <a href="https://github.com/laravel/laravel">Laravel</a> для создания и управления контентом простейших корпоративных сайтов, разворачиваемых на shared хостингах.

## Тестирование

Для успешного запуска тестов обязательным пунктом является очистка кэша конфигурации приложения. Нижеуказанные в этом разделе команды уже включают в себя предварительную очистку данного кэша. Повторное же кэширование конфигурации не предусмотрено этими командами.

Для запуска тестов используйте команду:

```console
composer run-script test
```

Для формирования agile-документации, генерируемой в HTML-формате и записываемой в файл [tests/testdox.html](tests/testdox.html), используйте команду:

```console
composer run-script testdox
```

## Лицензия

`BixBite` - программное обеспечение с открытым исходным кодом, распространяющееся по лицензии [MIT](LICENSE).
