<img src="https://bixbite.site/logo-git-tmp.png">

Система рассчитана на оперативное развертывание простейших сайтов на shared хостингах. Но этим не ограничивается, благодаря использованию <a href="https://github.com/laravel/laravel">Laravel</a> в качестве ядра.

## Необходимый набор инструментов

***Предостережение:*** выполняйте установку программного обеспечения и команды только поэтапно, не выполняйте всё разом! Не забывайте перезагрузить операционную систему перед пунктом [Установка BixBite](#Установка-bixbite).

Ссылки на некоторые ресурсы (XAMPP, Atom) носят субъективный характер.

- <a href="https://www.apachefriends.org/ru/index.html">XAMPP</a> - локальная среда веб разработки;
- <a href="https://getcomposer.org/Composer-Setup.exe">Composer</a> - пакетный менеджер для PHP;
- <a href="https://nodejs.org/en/download/">npm</a> - пакетный менеджер node.js;
- <a href="https://atom.io/">Atom</a> - текстовый редактор с поддержкой управления Git.

Небольшой список рекомендуемых пакетов для редактора Atom: atom-beautify, atom-ide-ui, docblockr, emmet, file-icons, highlight-selected, 
ide-php, language-blade.

## Установка BixBite

Прежде чем развертывать систему на хостинге, создаём её локальную копию. Необходимо это для комфортного редактирования шаблона. Всячески старайтесь избегать использования встроенного в систему редактора шаблонов. Но, обо всём по порядку.

Запустить командную строку с правами администратора, выполнить:

```
cd C:\xampp\htdocs
composer create-project russsiq/bixbite blog -s dev
cd blog
npm install --no-bin-links
```

При появлении запроса `Do you want to remove the existing VCS (.git, .svn..) history? [Y,n]?`, выбрать `n` для сохранения системы контроля версий. Аргумент `--no-bin-links` необходим при использовании операционной системы ***Windows***.

При возникновении сложностей на данном этапе, поблагодарить меня за то, что поленился отредактировать файл *webpack.mix.js* или молча выполнить команды: 

```
npm i -f
npm i -g cross-env
npm i -g webpack@latest
npm audit fix
```

Создать БД *http://localhost/phpmyadmin/server_databases.php* с кодировкой *utf8_unicode_ci*. Перейти по ссылке *http://localhost/blog/* и следовать инструкциям мастера установки. На завершающем этапе при использовании на локальном хостинге рекомендуется выставить галочку *Заполнить тестовыми данными* и снять *Использовать оригинальную тему*.

По завершению установки узнать название вашей темы, располагающейся в папке `/resources/themes/`. Как правило, название темы - транслитерированное название сайта. Скопировать или запомнить название темы - на следующем этапе эти познания пригодятся. Тему *default* можете удалить, дабы не вводила в заблуждения.

## Подготовка файлов

Отредактировать файлы:
- `.htaccess` - прежде всего переадресация на протокол *https*, если таковой будет использоваться. Добавить в исключения верификационные файлы поисковых систем (yandex, например) или социальных сетей (pinterest, например) - это всегда можно выполнить позже.
- `robots.txt` - разрешить доступ поисковым роботам к папке *public* вашей темы, заменив {theme} на название вашей темы, а {site} на ваш домен, с учетом будущего протокола.
- `webpack.mix.js` - заменить тему *default* на вашу. Также при использовании иконочных шрифтов, отличных от указанных, установить их предварительно и указать пути к ним для копирования по подобию как это сделано для *font-awesome*.

Если необходимо включить режим разработки и отобразить панель отладки, то в файле *.env* выставляем значения для двух строк:

```
APP_DEBUG=true
APP_ENV=dev
```

## Персонализация тем BixBite или гремучая смесь "зоопарка технологий"

Выполнить `npm run watch` - это есть запуск наблюдения за изменениями файлов в `/resources/themes/{theme}/assets/`.

Отредактировать тему. Редактируем, редактируем, сутки, вторые, третьи. По завершению редактирования в командной строке прервать наблюдение сочетанием клавиш *Ctrl+C*. Выполнить `npm run production` для сжатия и компиляции стилей и скриптов.

## Перенос дистрибутива на shared хостинг

1 Перед тем как выполнить перенос, подчистим кэш, т.е. вручную удалим файлы в папках: *bootstrap/cache*, *storage/debugbar*, *storage/framework/cache/data*, *storage/framework/views*, *storage/logs*. При удалении пропускайте (не удаляйте) *.gitignore*.

2 Следующее - удалим папку *storage/app/uploads/image* со всем содержимым в ней.

3 Важно. Папку *node_modules* не нужно удалять, а просто исключить из списка переносимых. Тоже самое относится к ярлыку *uploads* и к файлу *.env*, находящимся в корне проекта - исключаем их из набора.

4 По итогам создания архива должен получится файл размером 15-20 МБ.

5 Отправляем на хостинг, распаковываем, переходим по адресу будущего сайта, производим установку, не забыв предварительно создать БД.

6 Важно. На завершающем этапе выбрать созданную вами тему. По факту других и не должно быть. Там же выставить галочку *Использовать оригинальную тему* и снять с *Заполнить тестовыми данными*.

## Несколько англоязычных абзацев о фреймворке Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects.

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Лицензия

BixBite - программное обеспечение с открытым исходным кодом, распространяющееся по лицензии [MIT license](https://choosealicense.com/licenses/mit/).
