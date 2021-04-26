<template>
    <select class="form-select" size="8" style="height: 163px;" v-model="localValue" @change="handleChange" multiple>
        <option v-for="(item, index) in flattenItems" :value="item.id" :selected="item.selected" :disabled="item.disabled">{{ '&ndash;'.repeat(item.depth) }} {{ item.title }}</option>
    </select>
</template>

<script type="text/ecmascript-6">

export default {
    props: {
        items: {
            type: Object // || Array
        },
        selected: {
            type: Array
        },
    },

    data() {
        return {
            localValue: [],
            flattenItems: []
        }
    },

    mounted() {
        this.localValue = this.$props.selected
        this.flattenItems = this.flatten(this.$props.items, 0)
    },

    methods: {
        flatten(items, depth = 0) {
            let flattened = []

            for (let item in items) {
                let currentItem = items[item]
                currentItem.depth = depth
                currentItem.disabled = typeof currentItem.alt_url === 'string'
                currentItem.selected = this.selected.includes(item)

                flattened.push(currentItem)

                if (typeof currentItem.children == 'object') {
                    flattened = flattened.concat(
                        this.flatten(currentItem.children, depth + 1)
                    )
                }
            }

            return flattened
        },

        handleChange(event) {
            let options = event.target.options

            this.localValue = Array.from(options)
                .filter(option => option.selected)
                .map(option => Number(option.value))

            this.$emit('input', this.localValue)
        },

    }
}
</script>

<style scoped>
option:disabled {
    text-decoration: line-through;
}
</style>
