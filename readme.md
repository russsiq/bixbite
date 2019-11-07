<img src="https://raw.githubusercontent.com/russsiq/bixbite/master/resources/skins/default/public/favicon.ico">

Система рассчитана на оперативное развертывание простейших сайтов на shared хостингах. Расширяема благодаря использованию <a href="https://github.com/laravel/laravel">Laravel</a> в качестве ядра.

Содержание:

1. [Доступно или запланировано](#Доступно-или-запланировано)
1. [Требования](#Требования)
1. [Набор инструментов](#Необходимый-набор-инструментов)
1. [Установка](#Установка-bixbite)
1. [Подготовка файлов](#Подготовка-файлов)
1. [Персонализация тем](#Персонализация-тем-bixbite-или-гремучая-смесь-зоопарка-технологий)
1. [Перенос на хостинг](#Перенос-дистрибутива-на-shared-хостинг)
1. [Тестирование](#Тестирование)
1. [О Laravel](#Несколько-англоязычных-абзацев-о-фреймворке-laravel)
1. [Лицензия](#Лицензия)

## Доступно или запланировано

#### Карты сайта /sitemap.xml + турбо страницы яндекс /amp/articles.xml
- [x] Кэширование, создание карт для домашней страницы, категорий, записей и основных изображений, прикрепленных к ним, задание приоритета и частоты обновления.
- [ ] Добавить ссылки в админ. панели на очистку кэшей.
- [ ] Разбиение карты, если в ней записей больше 50000. chunk.
- [ ] Принудительное повышение приоритета записи, если она обновлена.

#### Записи
- [x] Создание, редактирование, удаление записей в админ. панели.
- [x] Задание категорий, тегов записи, статуса записи (черновик, на модерации, опубликована), прикрепление основного изображения записи, задание своих meta (*description*, *keywords*, *robots*), время публикации, разрешение/запрет комментирования, добавление в избранное, отображение/скрытие с главной страницы, прикрепление на главной, прикрепление в категории.
- [x] Запись без категории всегда остается в черновиках.
- [x] При удалении пользователя, все его записи удаляются.
- [x] При удалении записи удаляется прикрепленные изображения и файлы, все комментарии к записи.
- [x] Вставка изображений в запись при создании/редактировании.
- [x] Прикрепление файлов к записи при создании/редактировании.
- [x] Поддержка *shortcodes*, включая доп.поля в контенте записи.
- [ ] Создать раздел или настройки в записях для управления шорткодами.
- [ ] Создать ссылки на шорткоды в визуальном редакторе.

#### Категории
- [x] Создание, редактирование, удаление категорий в админ. панели.
- [x] Вложенность, изменение позиций, отображение/скрытие из меню, прикрепление изображения, задание своих meta (*description*, *keywords*), отображение информации о категории на первой странице категории, выбор количества выводимых записей в категории, а также способ и направление сортировки, индивидуальные шаблоны для категорий/записей. См. директорию *custom_views*.
- [x] Категория, содержащая записи, не удаляется.

#### Теги
- [x] Задание разделителя, кириллические теги, мета тег robots: *noindex, follow* для страницы тегов и страниц по тегу. А то яша, как бык помои их жрет.
- [x] Удаление неиспользуемых тегов из настроек плагина.
- [ ] Создать учет количества просмотров для сортировки по популярности, например, для виджета *Популярные теги*.

#### Пользователи
- [x] Создание, редактирование, удаление пользователей в админ. панели.
- [x] Группы пользователей и привилегии для групп, регистрация пользователей, редактирование своего профиля, стена пользователя с возможностью оставлять комментарии другими пользователями, там же, ссылка на страницу с записями пользователя.
- [ ] Восстановление пароля.

#### Комментарии
- [x] Редактирование, удаление комментариев в админ. панели и без входа в админ. панель.
- [x] Древовидные комментарии.
- [x] При удалении комментария, все дочерние комментарии удаляются. При удалении пользователя, все его комментарии остаются, но как от незарегистрированного пользователя.
- [ ] Добавить оповещение автора записи и других заинтересованных лиц об оставленном комментарии. Добавить выбор многостраничного отображения комментариев к записи. Тормозит сам вид ЧПУ ссылки. Премодерация с возможностью из почтового ящика при переходе по ссылке удалить/одобрить коммент.

#### Файлы
- [x] Редактирование, удаление файлов в админ. панели.
- [x] Массовая загрузка в админ. панели. Позже ***удалить*** эту возможность.
- [x] Разбивка по mime типу файлов при загрузке. Редактирование названия и описания файла.

#### Виджеты
- [x] Кэширование. Задание шаблона, заголовка.
- [x] Виджет *Популярные записи*.
- - [x] выбор категории, id записей, пользователя, за последние дни, из избранных, количество выводимых записей, способ и направление сортировки, по состоянию (опубликованные и т.д.);
- [x] Виджет *Соседние записи*.
- - [x] просто вывод ссылок;
- - [ ] добавить возможность выбора только из текущей категории.
- [x] Виджет *Похожие записи*.
- - [x] выбор количества выводимых записей, способ и направление сортировки.
- - [ ] добавить выбор критерии схожести: теги, релевантность.
- [x] Виджет *Архив записей*.
- - [x] просто вывод ссылок по месяцу и году;
- - [x] выбор количества выводимых записей.
- [x] Виджет *Обсуждения* (последние комментарии к записям).
- [x] Виджет *Теги*.
- - [x] выбор количества выводимых записей, способ и направление сортировки.


#### Опросы
- [x] Потрачено.

#### Доп. поля
- [x] 2018-07-14 Реализованы доп.поля для Записей, категорий, пользователей.

#### Динамический фильтр записей
- [x] Ничего не сделано, но должно быть на vue.js.

## Требования
Во время установки производится проверка по минимальным требованиям:
- ✓ PHP >= 7.2.2
- ✓ PDO mySql/MariaDB
- ✓ OpenSSL
- ✓ GD
- ✓ Fileinfo
- ✓ Mbstring
- ✓ Tokenizer
- ✓ Ctype
- ✓ JSON
- ✓ XML
- ✓ ZLib

## Необходимый набор инструментов
***Предостережение:*** выполняйте установку программного обеспечения и команды только поэтапно, не выполняйте всё разом! Не забывайте перезагрузить операционную систему перед пунктом [Установка BixBite](#Установка-bixbite).

Ссылки на некоторые ресурсы (XAMPP, Atom) носят субъективный характер.

- <a href="https://www.apachefriends.org/ru/index.html">XAMPP</a> - локальная среда веб разработки;
- <a href="https://getcomposer.org/Composer-Setup.exe">Composer</a> - пакетный менеджер для PHP;
- <a href="https://nodejs.org/en/download/">npm</a> - пакетный менеджер node.js;
- <a href="https://atom.io/">Atom</a> - текстовый редактор с поддержкой управления Git.

Небольшой список рекомендуемых пакетов для редактора Atom: *atom-beautify*, *atom-ide-ui*, *docblockr*, *emmet*, *file-icons*, *highlight-selected*, *ide-php*, *language-blade*, *html-to-css*, *language-vue*.

## Установка BixBite
Прежде чем развертывать систему на хостинге, создаём её локальную копию. Необходимо это для комфортного редактирования шаблона. Всячески старайтесь избегать использования встроенного в систему редактора шаблонов. Но, обо всём по порядку.

Запустить командную строку с правами администратора, выполнить:

```console
cd C:\xampp\htdocs
composer create-project russsiq/bixbite blog -s dev
cd blog
npm install --no-bin-links
```

При появлении запроса `Do you want to remove the existing VCS (.git, .svn..) history? [Y,n]?`, выбрать `n` для сохранения системы контроля версий. Аргумент `--no-bin-links` необходим при использовании операционной системы ***Windows***.

При возникновении сложностей на данном этапе выполнить команды:

```console
npm i -f
npm i -g cross-env
npm i -g webpack@latest
npm audit fix
```

Создать БД *http://localhost/phpmyadmin/* с кодировкой *utf8mb4_unicode_ci*. Перейти по ссылке *http://localhost/blog/* и следовать инструкциям мастера установки. На завершающем этапе при использовании на локальном хостинге рекомендуется выставить галочку *Заполнить тестовыми данными* и снять *Использовать оригинальную тему*.

**Важно** При первом посещении админ. панели выполните настройки системы и модулей и обязательно **Комплексную оптимизацию**.

По завершению установки узнать название вашей темы, располагающейся в директории `/resources/themes/`. Как правило, название темы - транслитерированное название сайта. Тему *default* можете удалить, дабы не вводила в заблуждения.

## Подготовка файлов
Отредактировать файлы:
- `.htaccess` - прежде всего переадресация на протокол *https*, если таковой будет использоваться. Добавить в исключения верификационные файлы поисковых систем (*yandex*, например) или социальных сетей (*pinterest*, например) - это всегда можно выполнить позже.
- `robots.txt` - разрешить доступ поисковым роботам к директории *public* вашей темы, заменив `{theme}` на название вашей темы, а `{site}` на ваш домен, с учетом будущего протокола.
- `webpack.mix.js` - при использовании иконочных шрифтов, отличных от указанных, установить их предварительно и указать пути к ним для копирования по подобию как это сделано для *font-awesome*.

На *localhost* для настройки почтового клиента воспользуйтесь сервисом [Mailtrap](https://mailtrap.io):
 - создайте почтовый ящик в данном сервисе;
 - в настройках во вкладке *SMTP Settings* в выпадающем списке выберите *PHP -> Laravel*;
 - скопируйте каждую из строк предложенных настроек в файл `.env` корня сайта.

**Внимание** Повторяющихся строк в данном файле быть не должно.

Пример настроек, предлагаемых сервисом Mailtrap:
```ini
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=секретные_буквы_цифры
MAIL_PASSWORD=секретные_буквы_цифры
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME=Example
```

Если необходимо включить режим разработки и отобразить панель отладки, то в файле *.env* выставляем значения для двух строк:

```ini
APP_DEBUG=true
APP_ENV=dev
```

## Персонализация тем BixBite или гремучая смесь "зоопарка технологий"

Выполнить в командной строке `npm run watch` - это есть запуск наблюдения за изменениями файлов в `/resources/themes/{theme}/assets/`. Чтобы прервать наблюдение можете использовать сочетание клавиш *Ctrl+C*.

Отредактировать тему. Редактируем, редактируем, сутки, вторые, третьи. По завершению редактирования выполнить `npm run prod` для сжатия и компиляции стилей и скриптов.

## Перенос дистрибутива на shared хостинг

**1** Перед тем как выполнить перенос, подчистим кэш, т.е. вручную удалим файлы в директориях:
- *bootstrap/cache*,
- *storage/debugbar*,
- *storage/framework/cache/data*,
- *storage/framework/views*,
- *storage/logs*.

При удалении пропускайте (не удаляйте) *.gitignore*.

**2** Следующее - удалим директорию *storage/app/uploads/image* со всем содержимым в ней.

**3** **Важно** Директорию *node_modules* не нужно удалять, а просто исключить из списка переносимых. Тоже самое относится к ярлыку *uploads* и к файлу *.env*, находящимся в корне проекта - исключаем их из набора.

**4** По итогам создания архива должен получится файл размером ~13.5 МБ.

**5** Отправляем на хостинг, распаковываем, переходим по адресу будущего сайта, производим установку, не забыв предварительно создать БД.

**6** **Важно** На завершающем этапе выбрать созданную вами тему. По факту других и не должно быть. Там же выставить галочку *Использовать оригинальную тему* и снять с *Заполнить тестовыми данными*.

**7** **Важно** При первом посещении админ. панели выполните настройки системы и модулей и обязательно **Комплексную оптимизацию**.

## Тестирование

Неа, не слышал.

## Несколько англоязычных абзацев о фреймворке Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects.

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Лицензия

BixBite - программное обеспечение с открытым исходным кодом, распространяющееся по лицензии [MIT](https://choosealicense.com/licenses/mit/).
