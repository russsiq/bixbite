<template>
<select class="form-control" @change="handleChange" multiple>
    <template v-for="(item, index) in categories">
        <option :value="item.id" :selected="item.selected" :disabled="item.disabled">{{ item.title }}</option>
    </template>
</select>
</template>

<script type="text/ecmascript-6">
import Category from '@/store/models/category';

export default {
    props: {
        value: {
            type: Array,
            default: [],
        }
    },

    data() {
        return {}
    },

    computed: {
        categories() {
            const categories = Category.query().orderBy('position', 'asc').all();
            const roots = this.getRootCategories(categories);

            return this.getNestedCategories(roots, 0, categories);
        },
    },

    methods: {
        getRootCategories(categories) {
            return categories.filter(element => !element.parent_id)
        },

        getNestedCategories(items, depth, categories) {
            let nested = [];

            items.forEach((item, index, array) => {
                item = item.$toJson();

                item.depth = depth;
                item.disabled = 'string' === typeof item.alt_url;
                item.selected = this.$props.value.includes(item.id);

                item.title = ' — '.repeat(item.depth) + item.title;

                nested.push(item);

                const children = categories.filter(element => element.parent_id === item.id);

                if (children.length) {
                    nested = nested.concat(this.getNestedCategories(children, depth + 1, categories));
                }
            });

            return nested;
        },

        handleChange(event) {
            this.$emit(
                'input',
                Array.from(event.target.options)
                .filter(option => option.selected)
                .map(option => Number(option.value))
            )
        },

    }
}
</script>

<style lang="scss" scoped>
select.form-control[multiple] {
    height: 159px;
}

option:disabled {
    text-decoration: line-through;
}
</style>
