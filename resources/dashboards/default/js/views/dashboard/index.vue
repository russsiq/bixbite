<template>
<div class="">
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="theme-card" style="background-image: url(../themes/default/images/screenshot.png)">
                <div class="color-overlay clearfix">
                    <div class="theme-content">
                        <div class="theme-header">
                            <h3 class="theme-title">default</h3>
                            <h4 class="theme-info"><a href="https://github.com/russsiq/bixbite" target="_blank">RusiQ</a>, v0.1 (2018-05-10)</h4>
                        </div>
                        <p class="theme-desc">Новостной шаблон. Описание шаблона на локальном языке из файла version.</p>
                        <!-- <a href="http://localhost/bixbite/admin/themes/settings" class="btn btn-outline-primary theme-btn">Настроить тему</a> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <aside class="widget widget_notes clearfix">
                <div class="widget_body">
                    <table class="table notes-list">
                        <tbody>
                            <tr v-for="note in notes" class="notes-item warning" :key="note.id">
                                <td>
                                    <router-link :to="{name: 'note.edit', params:{id: note.id}}" class="notes-title text-dark">{{ note.title }}</router-link>
                                    <p class="notes-description text-muted small">{{ note.created_at | dateToString }}</p>
                                    <p class="notes-description text-muted">{{ note.description }}</p>
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-link" @click="toggleComplete(note)"><b :class="note.toggleButtonClass"></b></button>
                                </td>
                            </tr>
                            <tr class="notes-item">
                                <td>
                                    <router-link :to="{name: 'notes'}" class="btn btn-outline-primary">Перейти к заметкам &raquo;</router-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </aside>
        </div>
    </div>

    <div v-if="modules.length" class="row">
        <div v-for="module in modules" class="col-sm-12 col-md-6 col-lg-4">
            <div class="card card-module">
                <img class="card-img-background" :src="dashboard('images/background_module.jpg')" />
                <router-link :to="module.name" class="card-module-icon" :title="module.title"><i class="fa" :class="module.icon"></i></router-link>
                <div class="card-body">
                    <h4 class="card-title">{{ module.title }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script type="text/ecmascript-6">
import Note from '@/store/models/note';

export default {
    name: 'dashboard',

    data() {
        return {
            noteCollection: [],
            modules: [{
                id: 1,
                name: 'articles',
                title: 'Записи',
                icon: 'fa-newspaper-o',
            }, {
                id: 2,
                name: 'categories',
                title: 'Категории',
                icon: 'fa-folder-open-o',
            }, {
                id: 3,
                name: 'attachments',
                title: 'Файловый менеджер',
                icon: 'fa-files-o',
            // }, {
            //     id: 4,
            //     name: 'tags',
            //     title: 'Теги',
            //     icon: 'fa-tags',
            }, {
                id: 5,
                name: 'users',
                title: 'Пользователи',
                icon: 'fa-users',
            }, {
                id: 6,
                name: 'comments',
                title: 'Комментарии',
                icon: 'fa-comments-o',
            // }, {
            //     id: 7,
            //     name: 'polls',
            //     title: 'Опросы',
            //     icon: 'fa-list-ol',
            }, {
                id: 8,
                name: 'x_fields',
                title: 'Дополнительные поля',
                icon: 'fa-columns',
            }, {
                id: 9,
                name: 'system',
                title: 'Система',
                icon: 'fa-list-alt',
            }],
        }
    },

    computed: {
        notes() {
            const noteCollection = this.noteCollection.filter((note) => {
                    return !note.is_completed;
                })
                .sort((b, a) => b.id < a.id ? 1: -1)
                .splice(0, 4)

            return noteCollection;
        },
    },

    mounted() {
        Note.$fetch()
            .then((notes) => {
                this.noteCollection = notes;
            });
    },

    methods: {
        toggleComplete(note) {
            Note.$update({
                params: {
                    id: note.id
                },

                data: {
                    ...note,
                    is_completed: !note.is_completed,
                }
            });
        },
    },
}
</script>
