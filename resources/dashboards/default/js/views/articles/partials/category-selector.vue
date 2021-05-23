<template>
<select class="form-select" @change="handleChange" :multiple="multiple">
    <template v-for="(item, index) in nestedCategories">
        <option :value="item.id" :selected="item.selected" :disabled="item.disabled" :key="item.id">{{ '&ndash;'.repeat(item.depth) }} {{ item.title }}</option>
    </template>
</select>
</template>

<script type="text/ecmascript-6">
import Category from '@/store/models/category';

export default {
    name: 'category-selector',

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
        },

        multiple: {
            type: Boolean,
            default: false,
        },
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
            const roots = this.categories.filter(element => ! element.parent_id);

            return this.resolveNestedCategories(roots, 0);
        },

        resolveNestedCategories() {
            return (items, depth) => {
                let nested = [];

                items.map((item, index, array) => {
                    item.depth = depth;
                    item.disabled = 'string' === typeof item.alt_url;
                    item.selected = this.$props.value.some(category => item.id === category.id);

                    nested.push(item);

                    const children = this.categories.filter(element => element.parent_id === item.id);

                    if (children.length) {
                        nested = nested.concat(this.resolveNestedCategories(children, depth + 1));
                    }
                });

                return nested;
            }
        },
    },

    mounted() {
        Category.$fetch()
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

            if (this.$props.multiple) {
                this.$emit(
                    'update:categories',
                    this.categories.filter(category => selected.includes(category.id))
                );
            } else {
                this.$emit(
                    'update:category',
                    this.categories.first(category => selected.includes(category.id))
                );
            }
        },
    }
}
</script>

<style lang="scss" scoped>
select.form-select[multiple] {
    min-height: 160px;
}

option:disabled {
    text-decoration: line-through;
}
</style>
