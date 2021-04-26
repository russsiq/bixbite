<template>
<select class="form-select" @change="handleChange" multiple>
    <template v-for="(item, index) in nestedCategories">
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
        },

        categoryable: {
            type: Object,
            required: true,
            validator(categoryable) {
                return 'number' === typeof categoryable.id &&
                    'string' === typeof categoryable.type;
            }
        }
    },

    data() {
        return {
            categories: []
        }
    },

    computed: {
        selected() {
            return this.value.map(category => category.id);
        },

        nestedCategories() {
            const categories = this.categories;
            const roots = categories.filter(element => !element.parent_id);

            return this.getNestedCategories(roots, 0, categories);
        },

        getNestedCategories() {
            return (items, depth, categories) => {
                let nested = [];

                items.forEach((item, index, array) => {
                    item.depth = depth;
                    item.disabled = 'string' === typeof item.alt_url;
                    item.selected = this.$props.value.some(category => item.id === category.id);
                    item.title = ' — '.repeat(item.depth) + item.title;

                    nested.push(item);

                    const children = categories.filter(element => element.parent_id === item.id);

                    if (children.length) {
                        nested = nested.concat(this.getNestedCategories(children, depth + 1, categories));
                    }
                });

                return nested;
            }
        },
    },

    created() {
        Category.$fetch({

            })
            .then(this.fillForm);
    },

    methods: {
        fillForm(categories) {
            this.categories = categories;
        },

        handleChange(event) {
            const selected = Array.from(event.target.options)
                .filter(option => option.selected)
                .map(option => Number(option.value));

            this.$emit(
                'update:categories',
                this.categories.filter(category => selected.includes(category.id))
            );
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
