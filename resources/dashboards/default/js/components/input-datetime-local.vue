<template>
<input type="datetime-local" :value="localValue" @input="onInputHandler" />
</template>

<script type="text/ecmascript-6">
export default {
    name: 'input-datetime-local',

    props: {
        value: {
            type: [String, Date],
            default: null,
            required: false
        }
    },

    data() {
        return {}
    },

    computed: {
        localValue() {
            return this.$props.value && this.$props.value.replace(' ', 'T').slice(0, 16);
        }
    },

    methods: {
        onInputHandler(event) {
            this.$emit('input', event.target.value);
        }
    }
}
</script>
