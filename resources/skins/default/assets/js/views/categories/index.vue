<template>
<div class="filterable">
    <div class="card d-print-none">
        <div class="card-header d-flex">
            <router-link :to="{name: 'categories.create'}" class="btn btn-outline-dark" :title="'Create' | trans">
                <i class="fa fa-plus"></i> {{ 'Create' | trans }}
            </router-link>
            <div class="btn-group d-flex ml-auto">
                <a :href="url('app_common/clearcache/categories|navigation')" :title="'Clear' | trans" class="btn btn-outline-dark">
                    <i class="fa fa-recycle"></i>
                </a>
                <router-link :to="{name: 'x_fields'}" class="btn btn-outline-dark" :title="'XFields' | trans">
                    <span class="as-icon">χφ</span>
                </router-link>
                <router-link :to="{name: 'categories.settings'}" class="btn btn-outline-dark" title="Настройки">
                    <i class="fa fa-cogs"></i>
                </router-link>
            </div>
            <div class="btn-group ml-auto">
                <button type="button" class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>

    <div class="card">
        <div v-if="categories.length" class="card-header d-print-none">
            <button type="button" class="btn btn-outline-success">{{ 'Save' | trans }}</button>
            <button type="button" class="btn btn-outline-warning" onclick="return confirm('@lang('msg.sure')');">{{ 'Reset' | trans }}</button>
        </div>

        <!-- LIST OF COLLECTION -->
        <div class="card-body">
            <div v-if="categories.length" class="dd nestable-with-handle">
                <ol class="dd-list">
                    <template v-for="item in categories">
                        <!-- <category-item :item="item" :count="categories.length" @up="upCategory" @down="downCategory"></category-item> -->
                        <li class="dd-item dd3-item" :data-id="item.id" draggable="true" @dragstart="dragStart(item.id, $event)" @dragover.prevent @dragenter="dragEnter" @dragleave="dragLeave" @dragend="dragEnd" @drop="dragFinish(item.id, $event)">
                            <div class="dd-handle dd3-handle btn btn-outline-dark"><i class="fa fa-bars"></i></div>
                            <div class="dd3-content">

                                <router-link :to="{name: 'categories.edit', params:{id: item.id}}">{{ item.title }}</router-link>

                                <sup>[id: {{ item.id }}, {{ item.articles_count || __('No') }}]</sup>

                                <div class="btn-group pull-right">
                                    <button type="button" v-if="item.info" :title="item.info" class="btn btn-link text-dark" readonly>
                                        <i class="fa fa-info"></i>
                                    </button>
                                    <button type="button" v-if="item.image_id" title="" class="btn btn-link text-dark" readonly>
                                        <i class="fa fa-file-image-o"></i>
                                    </button>
                                    <!-- <button type="submit" class="btn btn-link" formaction="{{ route('toggle.attribute', ['Category', item.id, 'show_in_menu']) }}" name="_method" value="PUT">
                                    <i class="fa {{ item.show_in_menu ? 'fa-eye text-success' : 'fa-eye-slash text-danger' }}"></i>
                                </button> -->

                                    <button class="btn btn-link text-dark" readonly>
                                        {{ item.position }}
                                    </button>

                                    <a :href="item.url" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>
                                    <router-link :to="{name: 'categories.edit', params:{id: item.id}}" class="btn btn-link"><i class="fa fa-pencil"></i></router-link>

                                    <button type="button" class="btn btn-link" :disabled="!!item.articles_count" @click.prevent="destroy(item)">
                                        <i class="fa fa-trash-o" :class="{'text-danger': !item.articles_count}"></i>
                                    </button>

                                    <!-- @if (! item.articles_count)
                                @can ('admin.categories.delete', $item)
                                <button type="submit" class="btn btn-link" onclick="return confirm('@lang('msg.sure')');" formaction="{{ route('admin.categories.delete', $item) }}" name="_method" value="DELETE">
                                    <i class="fa fa-trash-o text-danger"></i>
                                </button>
                                @endcan
                                @else
                                <button type="button" class="btn btn-link" disabled>
                                    <i class="fa fa-trash-o text-muted"></i>
                                </button>
                                @endif -->
                                </div>
                            </div>

                            <ol v-if="Boolean(item.children)" class="dd-list">
                                <template v-for="children in item.children">
                                    <category-item :item="children" :count="item.children.length" @up="$emit('up', item)" @down="$emit('down', item)"></category-item>
                                </template>
                            </ol>
                            <!-- .children -->
                        </li>
                    </template>
                    <!-- @each('categories.partials.index_categories', $categories, 'item') -->
                </ol>
            </div>
            <div v-else>
                <p class="alert alert-info text-center">Нет информации для отображения.</p>
            </div>
        </div>

        <div v-if="categories.length" class="card-footer d-print-none">
            <button type="button" class="btn btn-outline-success">{{ 'Save' | trans }}</button>
            <button type="button" class="btn btn-outline-warning" onclick="return confirm('@lang('msg.sure')');">{{ 'Reset' | trans }}</button>
        </div>

        <!-- <component v-bind:is="'currentStory'"></component> -->
    </div>
</div>
</template>

<script type="text/ecmascript-6">
// import categoryItem from './partials/category-item'

export default {
    name: 'categories',

    components: {
        // 'category-item': categoryItem,
        // 'currentStory': () => {
        //     // return import(`@/components/stories/dnevnik.vue`)
        // }
    },

    props: {
        model: {
            type: Function,
            required: true,
        },
    },

    data() {
        return {
            // categories: [],
            dragging: -1,
            loading: false,
            hasTouch: 'ontouchstart' in window
        }
    },

    computed: {
        categories() {
            return this.model.query()
                .withAll()
                .orderBy('position', 'asc')
                .get()
        },

        isDragging() {
            return this.dragging > -1
        },
    },

    async created() {
        // console.log(this.hasTouch)
        this.init()
    },

    methods: {
        /**
         * Reconstruction of the positions of categories.
         */
        init() {
            console.log(this.categories);
            // this.categories.forEach((item, index, array) => {
            //     // console.log(item)
            //     this.$props.model.update({
            //         id: item.id,
            //         // position: Math.max(--item.position, xps)
            //         position: index + 1
            //     })
            // })
        },

        create() {
            //
        },

        dragStart(id, event) {
            event.dataTransfer.setData('text/plain', id)
            event.dataTransfer.dropEffect = 'move'
            this.dragging = id
        },

        dragEnter(event) {
            //
        },

        dragLeave(event) {
            //
        },

        dragEnd(event) {
            this.dragging = -1
        },

        dragFinish(id, event) {
            this.moveItem(this.dragging, id)
        },

        moveItem(fromId, toId) {
            const categories = this.categories
            const from = categories.find((element, index, array) => element.id === fromId)
            const to = categories.find((element, index, array) => element.id === toId)

            const min = Math.min(from.position, to.position)
            const max = Math.max(from.position, to.position)
            const inRange = position => position >= min && position <= max

            this.$props.model.update({
                where: record => inRange(record.position) && record.id !== from.id,
                data(record) {
                    record.position = from.position < to.position ? --record.position : ++record.position
                }
            });

            // this.$props.model.update({
            //     id: from.id,
            //     position: to.position
            // });
        },

        /**
         * Delete the category.
         */
        async destroy(category) {
            if (confirm(__(`Are you sure you want to delete this category: ${category.id}?`))) {
                await this.$props.model.$delete({
                    params: {
                        id: category.id
                    }
                })
            }
        },
        //
        // removeItemAt(index) {
        //     this.todos.splice(index, 1)
        // },
    },
}
</script>

<style lang="scss">
/**
 * Nestable
 */

.dd {
    position: relative;
    display: block;
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 13px;
    line-height: 20px;
}

.dd-list {
    display: block;
    position: relative;
    margin: 0;
    padding: 0;
    list-style: none;
}

.dd-list .dd-list {
    padding-left: 30px;
}

.dd-collapsed .dd-list {
    display: none;
}

.dd-empty,
.dd-item,
.dd-placeholder {
    display: block;
    position: relative;
    margin: 0;
    padding: 0;
}

.dd-handle {
    display: block;
    height: 32px;
    margin: 5px 0;
    padding: 5px 10px;
    color: #333;
    text-decoration: none;
    font-weight: bold;
    border: 1px solid #ccc;
    background: #fafafa;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    line-height: 22px;
}

.dd-item > button {
    display: block;
    position: relative;
    cursor: pointer;
    float: left;
    width: 25px;
    height: 20px;
    margin: 6px 0;
    padding: 0;
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
    border: 0;
    background: transparent;
    font-size: 18px;
    line-height: 1;
    text-align: center;
    font-weight: bold;
}

.dd-item > button:before {
    content: '+';
    display: block;
    position: absolute;
    width: 100%;
    text-align: center;
    text-indent: 0;
}

.dd-item > button[data-action="collapse"]:before {
    content: '-';
}

.dd-empty,
.dd-placeholder {
    margin: 5px 0;
    padding: 0;
    min-height: 30px;
    background: #f2fbff;
    border: 1px dashed #b6bcbf;
    box-sizing: border-box;
}

.dd-empty {
    border: 1px dashed #bbb;
    min-height: 100px;
    background-color: #e5e5e5;
    background-image: linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-size: 60px 60px;
    background-position: 0 0, 30px 30px;
}

.dd-dragel {
    position: absolute;
    pointer-events: none;
    z-index: 9999;
}

.dd-dragel > .dd-item .dd-handle {
    margin-top: 0;
}

.dd-dragel .dd-handle {
    box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
}

/**
 * Nestable Extras
 */

.nestable-lists {
    display: block;
    clear: both;
    padding: 30px 0;
    width: 100%;
    border: 0;
    border-top: 2px solid #ddd;
    border-bottom: 2px solid #ddd;
}

#nestable-menu {
    padding: 0;
    margin: 20px 0;
}

.nestable-dark-theme .dd-handle {
    color: #fff;
    border: 1px solid #999;
    background: #bbb;
}

.nestable-dark-theme .dd-item > button:before {
    color: #fff;
}

@media only screen and (min-width: 700px) {
    .dd {
        /*float: left;*/
        width: 100%;
    }
    .dd+.dd {
        margin-left: 2%;
    }
}

.dd-hover > .dd-handle {
    background: #2ea8e5 !important;
}

/**
 * Nestable Draggable Handles
 */

.dd3-content {
    display: block;
    margin: 7px 0;
    padding: 5px 10px 5px 40px;
    color: #333;
    text-decoration: none;
    border: 1px solid #ccc;
    background: #f0f0f0;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    cursor: default;
}

.dd-dragel > .dd3-item > .dd3-content {
    margin: 0;
}

.dd3-item > button {
    margin-left: 36px;
}

.dd3-handle {
    position: absolute;
    margin: 0;
    left: 0;
    top: 0;
    cursor: move !important;
    width: 36px;
    white-space: nowrap;
}

.dd3-content .btn {
    padding-top: 0;
    padding-bottom: 0;
}
</style>
