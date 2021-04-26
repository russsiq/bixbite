<template>
<div class="filterable">
    <div class="card d-print-none">
        <div class="card-header d-flex">
            <router-link :to="{name: 'categories.create'}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> Создать</router-link>
            <div class="btn-group d-flex ms-auto">
                <a :href="url('app_common/clearcache/categories|navigation')" :title="'Clear' | trans" class="btn btn-outline-dark">
                    <i class="fa fa-recycle"></i>
                </a>
                <router-link :to="{name: 'x_fields'}" class="btn btn-outline-dark" :title="'XFields' | trans">
                    <span class="as-icon">χφ</span>
                </router-link>
            </div>
            <div class="btn-group ms-auto">
                <button type="button" class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>

    <div class="card">
        <table v-if="collection.length" class="table table-sm mb-0">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Заголовок</th>
                    <th>Альтернативное имя</th>
                    <th>Шаблон</th>
                    <th>Меню</th>
                    <!-- <th><i class="fa fa-home"></i></th> -->
                    <th><i class="fa fa-info"></i></th>
                    <th><i class="fa fa-file-image-o"></i></th>
                    <th><i class="fa fa-newspaper-o"></i></th>
                    <th><i class="fa fa-eye"></i></th>
                    <th class="text-right">Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in collection">
                    <td></td>
                    <td>{{ item.id }}</td>
                    <td>
                        <router-link :to="{name: 'categories.edit', params:{id: item.id}}">{{ item.title }}</router-link>
                    </td>
                    <td>{{ item.slug }}</td>
                    <td>{{ item.template || 'Не задан' }}</td>
                    <td>
                        <i v-if="item.show_in_menu" title="Да" class="fa fa-check text-success"></i>
                        <i v-else title="Нет" class="fa fa-times text-danger"></i>
                    </td>
                    <!-- <td></td> -->
                    <td>
                        <i v-if="item.info" :title="item.info" class="fa fa-check text-success"></i>
                        <i v-else title="Нет" class="fa fa-times text-danger"></i>
                    </td>
                    <td>
                        <i v-if="item.image_id" title="Да" class="fa fa-check text-success"></i>
                        <i v-else title="Нет" class="fa fa-times text-danger"></i>
                    </td>
                    <td>{{ item.articles_count }}</td>
                    <td>{{ item.views }}</td>
                    <td class="text-right">
                        <!-- <div class="btn-group btn-group-sm" role="group">
                            <button type="button" @click.prevent="moveItem('up', item)" class="btn btn-link"><i class="fa fa-arrow-up"></i></button>
                            <button type="button" @click.prevent="moveItem('down', item)" class="btn btn-link"><i class="fa fa-arrow-down"></i></button>
                        </div> -->

                        <div class="btn-group btn-group-sm" role="group">
                            <a :href="item.url" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>
                        </div>

                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-link" :disabled="!!item.articles_count" @click.prevent="destroy(item)">
                                <i class="fa fa-trash-o" :class="{'text-danger': !item.articles_count}"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-else>
            <p class="alert alert-info text-center">Нет информации для отображения.</p>
        </div>
    </div>
</div>
</div>
</template>

<script type="text/ecmascript-6">
// import categoryItem from './partials/category-item';

export default {
    name: 'categories',

    components: {
        //
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            collection: [],
        }
    },

    computed: {},

    async mounted() {
        await this.loadFromJsonPath('categories');

        this.$props.model.$fetch()
            .then(this.fillTable);
    },

    methods: {
        fillTable(collection) {
            this.collection = collection;
        },

        // moveItem(fromId, toId) {
        //     const categories = this.collection
        //     const from = categories.find((element, index, array) => element.id === fromId)
        //     const to = categories.find((element, index, array) => element.id === toId)
        //
        //     const min = Math.min(from.position, to.position)
        //     const max = Math.max(from.position, to.position)
        //     const inRange = position => position >= min && position <= max
        //
        //     this.$props.model.update({
        //         where: record => inRange(record.position) && record.id !== from.id,
        //         data(record) {
        //             record.position = from.position < to.position ? --record.position : ++record.position
        //         }
        //     });
        //
        //     // this.$props.model.update({
        //     //     id: from.id,
        //     //     position: to.position
        //     // });
        // },

        destroy(entity) {
            const result = confirm(`Хотите удалить эту Категорию [${entity.title}]?`);

            result && this.$props.model.$delete({
                    params: {
                        id: entity.id
                    }
                })
                .then((response) => {
                    this.collection = this.collection.filter((item) => item.id !== entity.id);
                });
        },
    },
}
</script>
