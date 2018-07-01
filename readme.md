<img src="https://bixbite.site/logo-git-tmp.png">

Система расчитана на оперативное развертывание простейших сайтов на shared хостингах. Но этим не ограничивается, благодаря использованию <a href="https://github.com/laravel/laravel">Laravel</a> в качестве ядра.

## Необходимый набор инструментов

Ссылки на ресурсы носят сугубо субъективный характер.

- <a href="https://www.apachefriends.org/ru/index.html">XAMPP</a> - локальная среда веб разработки;
- <a href="https://getcomposer.org/Composer-Setup.exe">Composer</a> - пакетный менеджер для PHP;
- <a href="https://nodejs.org/en/download/">npm</a> - пакетный менеджер node.js;
- <a href="https://atom.io/">Atom</a> - текстовый редактор с поддержкой управления Git.

Небольшой список рекомендуемых пакетов для редактора Atom: atom-beautify, atom-ide-ui, docblockr, emmet, file-icons, highlight-selected, 
ide-php, language-blade.

## Установка BixBite

Запустить командную строку с правами администратора, выполнить:

```
cd C:\xampp\htdocs
composer create-project russsiq/bixbite blog -s dev
cd blog
npm install --no-bin-links
```

При запросе `Do you want to remove the existing VCS (.git, .svn..) history? [Y,n]?`, выбрать `n`. Аргументы `--no-bin-links` при использовании операционной системы ***Windows***. При возникновении сложностей на данном этапе: 

```
npm i -f
npm i -g cross-env
npm i -g webpack@latest
npm audit fix
```

Создать БД *http://localhost/phpmyadmin/server_databases.php* с кодировкой utf8_unicode_ci. Перейти по ссылке *http://localhost/blog/* и следовать инструкциям мастера установки. На завершающем этапе при использовании на локальном хостинге рекомендуется выставить галочку *Заполнить тестовыми данными* и снять *Использовать оригинальную тему*.

По завершению установки узнать название вашей темы, располагающейся в папке /resources/themes/. Как правило название темы - транслетированное название сайта. Скопировать или запомнить название темы - на следующем этапе это пригодится.

## Подготовка файлов

Отредактировать файлы:
- `.htaccess` - прежде всего переадресация на протокол *https*, если таковой будет использоваться. Добавить в исключения верификационные файлы поисковых систем (yandex, например) или социальных сетей (pinterest, например) - это всегда можно выполнить позже.
- `robots.txt` - разрешить доступ поисковых роботов к папке *public* вашей темы, заменив {theme} на название вашей темы, а {site} на ваш домен, с учетом будущего протокола.
- `webpack.mix.js` - заменить тему *default* на вашу. Также при использовании иконочных шрифтов, отличных от указанных, установить их предварительно и указать пути к ним для копирования по подобию как это сделано для *font-awesome*.

Если необходимо включить режим разработки, то в файле *.env* меняем значения для двух строк:

```
APP_DEBUG=true
APP_ENV=dev
```

## Первичная персонализация тем BixBite

Выполнить `npm run watch`. Отредактировать тему. По завершению редактирования в командной строке прервать наблюдения командой *Ctrl+C*. Выполнить `npm run production`. Вы всегда можете вернуться к редактированию темы запустив команду `npm run watch`, не забывайте - по завершению `npm run production` - для сжатия и компиляции стилей и скриптов.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell):

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[British Software Development](https://www.britishsoftware.co)**
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Pulse Storm](http://www.pulsestorm.net/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
