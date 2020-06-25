<template>
<div class="row">
    <div class="col col-sm-12">
        <form v-if="showedForm" method="post" @submit.prevent="onSubmit" novalidate>
            <ul class="nav nav-tabs">
                <li class="nav-item active">
                    <a href="#pane-main" class="nav-link active" data-toggle="tab">Основные настройки</a>
                </li>
                <li class="nav-item">
                    <a href="#pane-security" class="nav-link" data-toggle="tab">Безопасность</a>
                </li>
                <li class="nav-item">
                    <a href="#pane-sitemap" class="nav-link" data-toggle="tab">Карта сайта</a>
                </li>
                <li class="nav-item">
                    <a href="#pane-themes" class="nav-link" data-toggle="tab">Персонализация</a>
                </li>
            </ul>

            <br>

            <div class="tab-content">
                <div id="pane-main" class="tab-pane active">
                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Основные параметры</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Название сайта</label>
                                    <small class="form-text text-muted">Например: BixBite.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.app_name" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Домашняя страница</label>
                                    <small class="form-text text-muted">Например: http://server.com.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.app_url" class="form-control" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Мета данные</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Заголовок домашней страницы</label>
                                    <small class="form-text text-muted">Нет тайтла — нет поискового трафика.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.meta_title" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Разделитель при формировании заголовка</label>
                                    <small class="form-text text-muted">Общие правила для всех страниц.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.meta_title_delimiter" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Обратить порядок формирования заголовка</label>
                                    <small class="form-text text-muted">Меняет местами Заголовок сайта и Заголовок целевой страницы. Общие правила для всех страниц.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.meta_title_reverse" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Описание домашней страницы</label>
                                </div>
                                <div class="col-sm-5">
                                    <textarea v-model="form.meta_description" class="form-control" rows="1" @keydown.enter.prevent></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Ключевые слова домашней страницы</label>
                                </div>
                                <div class="col-sm-5">
                                    <textarea v-model="form.meta_keywords" class="form-control" rows="1" @keydown.enter.prevent></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Официальные данные об организации</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Название организации</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.org_name" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Населенный пункт (страна, область/край, город)</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.org_address_locality" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Адрес (улица, дом/строение)</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.org_address_street" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Контактный телефон</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.org_contact_telephone" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Контактный email</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="email" v-model="form.org_contact_email" class="form-control" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div id="pane-security" class="tab-pane">
                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Блокировка сайта</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Заблокировать сайт</label>
                                    <small class="form-text text-muted">Сайт делается неактивным.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.lock" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Причина блокировки сайта</label>
                                    <small class="form-text text-muted">Укажите причину по которой сайт был заблокирован. Например: <code>Технические работы</code>.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.reason" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Повторить попытку</label>
                                    <small class="form-text text-muted">Укажите через какое время боту поисковой системы необходимо повторно вернуться на сайт.<br>По умолчанию: <b>3600</b> сек.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model="form.retry" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Стандартная капча</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Использовать встроенную капчу</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.captcha_used" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Ширина изображения</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model="form.captcha_width" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Высота изображения</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model="form.captcha_height" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Семейство шрифтов</label>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.captcha_font_family" class="form-control" readonly>
                                        <option v-for="font in meta.fonts" :value="font">{{ font }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Размер шрифта</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model="form.captcha_font_size" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pane-sitemap" class="tab-pane">
                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa fa-th-list"></i> Домашняя страница
                            <sup><a :href="url('sitemap.xml')" target="_blank"><i class="fa fa-external-link"></i></a></sup>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Вероятная частота изменения домашней страницы</label>
                                    <small class="form-text text-muted"><a href="https://www.sitemaps.org/ru/protocol.html" target="_blank">Подробнее</a></small>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.home_changefreq" class="form-control">
                                        <option v-for="frequency in changefreq" :value="frequency">{{ frequency }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Приоритетность относительно других <i>url</i> на сайте</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model="form.home_priority" min="0.1" max="1.0" step="0.1" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa fa-th-list"></i> Страницы категорий
                            <sup><a :href="url('sitemap-categories.xml')" target="_blank"><i class="fa fa-external-link"></i></a></sup>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Вероятная частота изменения страниц</label>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.categories_changefreq" class="form-control">
                                        <option v-for="frequency in changefreq" :value="frequency">{{ frequency }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Приоритетность относительно других <i>url</i> на сайте</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model="form.categories_priority" min="0.1" max="1.0" step="0.1" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa fa-th-list"></i> Целевые страницы записей
                            <sup><a :href="url('sitemap-articles.xml')" target="_blank"><i class="fa fa-external-link"></i></a></sup>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Вероятная частота изменения страниц</label>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.articles_changefreq" class="form-control">
                                        <option v-for="frequency in changefreq" :value="frequency">{{ frequency }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Приоритетность относительно других <i>url</i> на сайте</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model="form.articles_priority" min="0.1" max="1.0" step="0.1" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa fa-th-list"></i> Турбо страницы Яндекс
                            <sup><a :href="url('amp-articles.xml')" target="_blank"><i class="fa fa-external-link"></i></a></sup>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Вероятная частота изменения страниц</label>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.amp_articles_changefreq" class="form-control">
                                        <option v-for="frequency in changefreq" :value="frequency">{{ frequency }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pane-themes" class="tab-pane">
                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Основные параметры</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Шаблон сайта</label>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.app_theme" class="form-control">
                                        <option v-for="theme in meta.themes" :value="theme">{{ theme }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Шаблон админ. панели</label>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.app_skin" class="form-control">
                                        <option v-for="skin in meta.skins" :value="skin">{{ skin }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Язык интерфейса</label>
                                    <small class="form-text text-muted">В текущей версии CMS не влияет на шаблон админ. панели.</small>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.app_locale" class="form-control">
                                        <option v-for="lang in meta.lang" :value="lang">{{ lang }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Правила транслитерации ярлыков</label>
                                    <small class="form-text text-muted">Данное правило будет применено при автоматической транслитерации дружественных фрагментов URL-адресов Категорий и Записей.</small>
                                </div>
                                <div class="col-sm-5">
                                    <select v-model="form.translite_code" class="form-control">
                                        <option value="en">en</option>
                                        <option value="ru">ru</option>
                                        <option value="ru__passport_2013">ru__passport_2013</option>
                                        <option value="ru__gost_2000_b">ru__gost_2000_b</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-7">
                                    <label class="control-label">Использовать персонализированную домашнюю страницу</label>
                                    <small class="form-text text-muted">Нет - на главной странице будет выведен список последних записей.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.homepage_personalized" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-5 offset-sm-7">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-outline-success btn-bg-white">
                                    <span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
                                    <span class="d-none d-md-inline">Сохранить</span>
                                </button>
                                <router-link :to="{name: 'dashboard'}" class="btn btn-outline-dark btn-bg-white ml-auto" exact>
                                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                                    <span class="d-none d-lg-inline">Отменить</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script type="text/ecmascript-6">
import Module from '@/views/settings/module.js';

export default {
    name: 'system',

    extends: Module,

    data() {
        return {
            entity: 'system',
            changefreq: [
                'always',
                'hourly',
                'daily',
                'weekly',
                'monthly',
                'yearly',
                'never',
            ],
        }
    },
}
</script>
