<template>
<div class="multi-category-selector">
    <div v-for="(item, index) in nestedCategories" :key="item.id" class="d-flex justify-content-between">
        <div class="form-check">
            <input
                :id="`multi-category-radio-${item.id}`"
                class="form-check-input"
                type="radio"
                v-model="mainCategory"
                :value="item.id"
                :disabled="item.disabled" />
            <label :for="`multi-category-radio-${item.id}`" class="form-check-label">
                {{ '–'.repeat(item.depth) }} {{ item.title }}
            </label>
        </div>
        <div class="form-check">
            <input
                :id="`multi-category-checkbox-${item.id}`"
                class="form-check-input"
                type="checkbox"
                v-model="checked"
                :value="item.id"
                :checked="item.checked"
                :disabled="item.disabled || mainCategory === item.id" />
            <label :for="`multi-category-checkbox-${item.id}`" class="form-check-label" />
        </div>
    </div>
    <pre v-if="isDebug" class="debug_box">
        {{ mainCategory }} {{ checked }} {{ nestedCategories }}
    </pre>
</div>
</template>

<script>
import debounce from 'lodash/debounce';

import Category from '@/store/models/category';

export default {
    name: 'multi-category-selector',

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
    },

    data() {
        return {
            mainCategory: null,
            checked: [],
            categories: []
        }
    },

    computed: {
        isDebug() {
            return process.env.NODE_ENV !== 'production';
        },

        nestedCategories() {
            const roots = this.categories.filter(element => ! element.parent_id);

            return this.resolveNestedCategories(roots, 0);
        },

        resolveNestedCategories() {
            return (items, depth) => {
                let nested = [];

                items.map((item, index, array) => {
                    const children = this.categories.filter(element => element.parent_id === item.id);

                    item.depth = depth;
                    item.disabled = 'string' === typeof item.alt_url || children.length > 0;

                    nested.push(item);

                    if (children.length) {
                        nested = nested.concat(this.resolveNestedCategories(children, depth + 1));
                    }
                });

                return nested;
            }
        },

        categoryables() {
            const categoryables = [];

            if (this.mainCategory) {
                categoryables.push({
                    category_id: this.mainCategory,
                    is_main: true,
                });
            }

            this.checked.map(id => categoryables.push({
                category_id: id,
                is_main: false,
            }));

            return categoryables;
        },
    },

    mounted() {
        this.debouncedSyncCategoryables = debounce(this.syncCategoryables, 888);

        Category.$fetch()
            .then(this.fillForm);
    },

    methods: {
        fillForm(categories) {
            this.categories = categories;

            this.checked = this.value.map(category => category.id);

            // Устанавливаем наблюдателя за массивом отмеченных `checkbox`.
            this.checkedWatcher = this.$watch('checked', this.handleChecked, {
                // deep: true,
                immediate: true,
            });

            this.mainCategoryWatcher = this.$watch('mainCategory', this.handleMainCategory, {
                // deep: true,
                immediate: true,
            });

            this.categoryablesWatcher = this.$watch('categoryables', this.debouncedSyncCategoryables, {
                deep: true,
                // immediate: true,
            });
        },

        handleChecked(newChecked, oldChecked) {
            if (! this.mainCategory && newChecked.length > 0) {

                const isMain = this.categories.find(category => newChecked.includes(category.id) && category.is_main);

                const index = isMain ? newChecked.findIndex(checked => isMain.id === checked) : 0;

                [this.mainCategory] = newChecked.splice(index, 1);
            }
        },

        handleMainCategory(newMainCategory, oldMainCategory) {
            if (newMainCategory && this.checked.includes(newMainCategory)) {
                const index = this.checked.findIndex(checked => newMainCategory === checked);

                this.checked.splice(index, 1);
            }
        },

        syncCategoryables(newRelation, oldRelation) {
            Category.$sync({
                categoryable: { ...this.categoryable },
            }, {
                categories: { ...newRelation },
            });
        },
    },
}
</script>

<style lang="scss" scoped>
.multi-category-selector {
    min-height: 198px;
    overflow-y: scroll;
    padding: 1rem 1rem;
}
</style>
