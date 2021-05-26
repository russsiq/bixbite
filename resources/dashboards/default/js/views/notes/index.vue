<template>
<filterable v-bind="filterable" :value="collection" @apply:change="fetch">
    <template #preaction>
        <router-link :to="{name: 'notes.create'}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Создать</router-link>
        <div class="btn-group ms-auto">
            <button class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
        </div>
    </template>

    <template #thead>
        <tr>
            <th>#</th>
            <th></th>
            <th>Заголовок</th>
            <th>Описание</th>
            <th class="text-right d-print-none">Действия</th>
            <!-- <th></th> -->
        </tr>
    </template>

    <template #row="{row: note}">
        <tr :key="note.id">
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
        </tr>
    </template>

    <template #tfoot>
        <tr>
            <td>#</td>
            <td></td>
            <td>Заголовок</td>
            <td>Описание</td>
            <td class="text-right d-print-none">Действия</td>
        </tr>
    </template>
</filterable>
</template>

<script type="text/ecmascript-6">
import Filterable from '../components/filterable.vue';

export default {
    name: 'notes',

    components: {
        'filterable': Filterable
    },

    props: {
        model: {
            type: Function,
            required: true
        },
    },

    data() {
        return {
            collection: [],
            filterable: {
                model: this.$props.model,
                active: false,
                massAction: false,
            }
        }
    },

    computed: {
        isCompleted() {
            return (note) => ({
                'fa fa-check text-success': note.is_completed,
                'fa fa-line-chart text-warning': !note.is_completed,
            })
        }
    },

    methods: {
        fetch(filter) {
            this.$props.model.$fetch({
                params: {
                    ...filter
                }
            })
                .then(this.fillTable);
        },

        fillTable(collection) {
            this.collection = collection;
        },

        toggleCompleted(note) {
            this.$props.model.$update(note.id, {
                ...note,
                is_completed: !note.is_completed,
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

            result && this.$props.model.$delete(note.id)
            .then((response) => {
                this.collection = this.collection.filter((item) => item.id !== note.id);
            });
        }
    },
}
</script>
