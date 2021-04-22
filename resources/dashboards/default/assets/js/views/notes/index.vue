<template>
<div class="filterable">
    <div class="card d-print-none">
        <div class="card-header d-flex">
            <router-link :to="{name: 'notes.create'}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Создать</router-link>
            <div class="btn-group ml-auto">
                <button class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>

    <div class="card card-table">
        <div class="card-header d-print-none"></div>

        <!-- LIST OF COLLECTION -->
        <div class="card-body table-responsive">
            <table v-if="collection.length" class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>Заголовок</th>
                        <th>Описание</th>
                        <th class="text-right d-print-none">Действия</th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="note in collection" :key="note.id">
                        <td>{{ note.id }}</td>
                        <td>
                            <button type="button" class="btn btn-link" @click="toggleCompleted(note)">
                                <b :class="isCompleted(note)"></b>
                            </button>
                        </td>
                        <td>
                            <router-link :to="{name: 'note.edit', params:{id: note.id}}">{{ note.title }}</router-link>
                        </td>
                        <td>
                            {{ note.description }}
                            <figure v-if="note.image && note.image.id">
                                <picture><img :src="note.image.url" :alt="note.slug" width="180" /></picture>
                                <figcaption>{{ note.image.title }}</figcaption>
                            </figure>
                        </td>
                        <td class="text-right d-print-none">
                            <div class="btn-group">
                                <router-link :to="{name: 'note.edit', params:{id: note.id}}" class="btn btn-link"><i class="fa fa-pencil"></i></router-link>
                                <button class="btn btn-link" :disabled="!note.is_completed" @click.prevent="destroy(note)">
                                    <i class="fa fa-trash-o" :class="{'text-danger': note.is_completed}"></i>
                                </button>
                            </div>
                        </td>
                        <!-- <td></td> -->
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>#</td>
                        <td></td>
                        <td>Заголовок</td>
                        <td>Описание</td>
                        <td class="text-right d-print-none">Действия</td>
                        <!-- <td></td> -->
                    </tr>
                </tfoot>
            </table>
            <p v-else-if="loading" class="alert alert-info text-center">Список загружается, пожалуйста, подождите ...</p>
            <p v-else class="alert alert-info text-center">Нет информации для отображения.</p>
        </div>
    </div>
</div>
</template>

<script type="text/ecmascript-6">
import {
    mapGetters
} from 'vuex';

export default {
    name: 'notes',

    props: {
        model: {
            type: Function,
            required: true
        },
    },

    data() {
        return {
            collection: [],
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
            loading: 'loadingLayer/show',
        }),

        isCompleted() {
            return (note) => ({
                'fa fa-check text-success': note.is_completed,
                'fa fa-line-chart text-warning': !note.is_completed,
            })
        }
    },

    mounted() {
        this.$props.model.$fetch()
            .then(this.fillTable);
    },

    methods: {
        fillTable(collection) {
            this.collection = collection;
        },

        toggleCompleted(note) {
            this.$props.model.$update({
                params: {
                    id: note.id
                },

                data: {
                    ...note,
                    is_completed: !note.is_completed,
                }
            })
            .then((updated) => {
                this.collection = this.collection.map((item) => {
                    if (item.id === note.id) {
                        return {
                            ...item,
                            ...updated
                        };
                    }

                    return item;
                });
            });
        },

        destroy(note) {
            const result = confirm(`Хотите удалить эту Заметку: [${note.title}] с прикрепленными файлами?`);

            result && this.$props.model.$delete({
                params: {
                    id: note.id
                }
            })
            .then((response) => {
                this.collection = this.collection.filter((item) => item.id !== note.id);
            });
        }
    },
}
</script>
