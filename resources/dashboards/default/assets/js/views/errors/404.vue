<template>
<div class="row">
    <div class="col-12">
        <div class="card text-center">
            <h2 class="my-5">404. Не найдено</h2>
            <p>Извините, но запрашиваемый вами ресурс или страница не найдены.</p>
        </div>
    </div>
</div>
</template>

<script type="text/ecmascript-6">
import VueCrumbsMixin from '@/mixins/vue-crumbs-mixin'

export default {
    mixins: [
        VueCrumbsMixin
    ],

    /**
     * The component's data.
     */
    data() {
        return {
            crumbs: [{
                title: '404. Not found'
            }],
        };
    },

    /**
     * Prepare the component.
     */
    mounted() {

    },

    methods: {
        //
    }
}
</script>
