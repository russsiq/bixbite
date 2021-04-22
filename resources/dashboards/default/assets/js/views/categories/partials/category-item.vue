<template>
<li class="dd-item dd3-item" :data-id="item.id"
    draggable="true"
    @dragstart="dragStart(item.id, $event)"
    @dragover.prevent
    @dragenter="dragEnter"
    @dragleave="dragLeave"
    @dragend="dragEnd"
    @drop="dragFinish(item.id, $event)">
    <div class="dd-handle dd3-handle btn btn-outline-dark"><i class="fa fa-bars"></i></div>
    <div class="dd3-content">

        <a href="#">{{ item.title }}</a>

        <!-- <small>[id: {{ item.id }}, {{ item.articles_count ?: __('No') }} {{ trans_choice('articles.num', item.articles_count) }}]</small> -->

        <div class="btn-group pull-right">
            <button v-if="item.info" :title="item.info" class="btn btn-link text-dark" readonly>
                <i class="fa fa-info"></i>
            </button>
            <button v-if="item.image_id" title="" class="btn btn-link text-dark" readonly>
                <i class="fa fa-file-image-o"></i>
            </button>
            <!-- <button type="submit" class="btn btn-link" formaction="{{ route('toggle.attribute', ['Category', item.id, 'show_in_menu']) }}" name="_method" value="PUT">
                <i class="fa {{ item.show_in_menu ? 'fa-eye text-success' : 'fa-eye-slash text-danger' }}"></i>
            </button> -->

            <button class="btn btn-link text-dark" readonly>
                {{ item.position }}
            </button>

             <!-- v-if="item.position < count" -->
            <button title="" class="btn btn-link text-dark" @click="$emit('down', item)">
                <i class="fa fa-arrow-down"></i>
            </button>

             <!-- v-if="item.position > 1" -->
            <button title="" class="btn btn-link text-dark" @click="$emit('up', item)">
                <i class="fa fa-arrow-up"></i>
            </button>

            <a :href="item.url" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>
            <a href="#" class="btn btn-link"><i class="fa fa-pencil"></i></a>

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

<script>
export default {
    name: 'category-item',

    components: {

    },

    props: {
        item: Object,
        count: Number,
    },

    data() {
        return {
            dragging: -1
        }
    },

    computed: {
        isDragging() {
            return this.dragging > -1
        }
    },

    async created() {

    },

    methods: {
        dragStart(id, event) {
            event.dataTransfer.setData('text/plain', id)
            event.dataTransfer.dropEffect = 'move'
            this.dragging = id
        },

        dragEnter(event) {
            console.log(event.target)
            // if (event.clientY > event.target.height / 2) {
            //     event.target.style.marginBottom = '10px'
            // } else {
            //     event.target.style.marginTop = '10px'
            // }
        },

        dragLeave(event) {
            // console.log(event.target)
            // event.target.style.marginTop = '2px'
            // event.target.style.marginBottom = '2px'
        },

        dragEnd(event) {
            this.dragging = -1
        },

        dragFinish(id, event) {
            this.moveItem(this.dragging, id)
            // event.target.style.marginTop = '2px'
            // event.target.style.marginBottom = '2px'
        },

        moveItem(from, to) {
            if (to === -1) {
                this.removeItemAt(from)
            } else {
                // this.todos.splice(to, 0, this.todos.splice(from, 1)[0])
            }
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
