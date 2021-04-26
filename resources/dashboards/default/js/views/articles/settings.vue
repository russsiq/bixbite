<template>
<div class="row">
    <div class="col col-sm-12">
        <form v-if="showedForm" method="post" @submit.prevent="onSubmit" novalidate>
            <ul class="nav nav-tabs">
                <li class="nav-item active">
                    <a href="#pane-main" class="nav-link active" data-toggle="tab">Основные настройки</a>
                </li>
                <li class="nav-item ">
                    <a href="#pane-create" class="nav-link" data-toggle="tab">Создание записи</a>
                </li>
            </ul>

            <br>

            <div class="tab-content">
                <div id="pane-main" class="tab-pane active">
                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Основные параметры</div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Вести подсчет просмотров записи</label>
                                    <small class="form-text text-muted">Дополнительный запрос к БД при нахождении на целевой странице записи.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.views_used" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Мета теги главной <a :href="url('articles')" target="_blank">страницы записей</a> на сайте</div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Заголовок</label>
                                    <small class="form-text text-muted">Нет тайтла — нет поискового трафика.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" v-model="form.meta_title" class="form-control" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Описание</label>
                                    <small class="form-text text-muted">Здесь можно ввести краткое описание страницы записей.</small>
                                </div>
                                <div class="col-sm-5">
                                    <textarea v-model="form.meta_description" class="form-control" rows="1" @keydown.enter.prevent></textarea>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Ключевые слова</label>
                                    <small class="form-text text-muted">Здесь можно ввести основные ключевые слова.</small>
                                </div>
                                <div class="col-sm-5">
                                    <textarea v-model="form.meta_keywords" class="form-control" rows="1" @keydown.enter.prevent></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Параметры по умолчанию</div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Количество записей</label>
                                    <small class="form-text text-muted">Число строк по умолчанию, извлекаемых из базы данных, для отображения на одной отдельно взятой странице или виджете.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model.number="form.paginate" min="8" class="form-control" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Сортировка записей</label>
                                    <small class="form-text text-muted">Сортировка записей при отображении на одной отдельно взятой странице или виджете.</small>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-select" v-model="form.order_by">
                                        <option value="id">Идентификатор</option>
                                        <option value="title">Заголовок</option>
                                        <option value="created_at">Создание</option>
                                        <option value="updated_at">Обновление</option>
                                        <!-- <option value="votes">Количество голосов</option> -->
                                        <!-- <option value="rating">Рейтинг пользователей</option> -->
                                        <option value="views">Количество просмотров</option>
                                        <option value="comments_count">Количество комментариев</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Порядок сортировки</label>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-select" v-model="form.direction">
                                        <option value="desc">По убыванию</option>
                                        <option value="asc">По возрастанию</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Количество символов в аннотации к записи</label>
                                    <small class="form-text text-muted">Если предисловие записи не указано, то оно будет сформировано путем обрезки начальной части текста записи.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model.number="form.teaser_length" min="20" max="255" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pane-create" class="tab-pane">
                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-th-list"></i> Основные параметры</div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Интервал автосохранения записи</label>
                                    <small class="form-text text-muted">Задаётся в секундах.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="number" v-model="form.save_interval" min="60" class="form-control" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Ручное создание ярлыка</label>
                                    <small class="form-text text-muted">Нет - автоматическое создание.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.manual_slug" disabled />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-7">
                                    <label class="control-label">Ручное формирование мета данных</label>
                                    <small class="form-text text-muted">Возможность самостоятельно задавать мета: robots, description, keywords персонально для каждой страницы.</small>
                                </div>
                                <div class="col-sm-5">
                                    <input type="checkbox" v-model="form.manual_meta" />
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
                                <router-link :to="{name: entity}" class="btn btn-outline-dark btn-bg-white ms-auto" exact>
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
    name: 'articles-settings',

    extends: Module,

    data() {
        return {
            entity: 'articles',
        }
    },
}
</script>
