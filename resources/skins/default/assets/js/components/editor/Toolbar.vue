<template>
<div id="toolbar-container">

    <!-- <span class="ql-formats">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Save</button>
            <div class="dropdown-menu">
                <button type="submit" name="state" value="published" class="dropdown-item">Published</button>
                <button type="submit" name="state" value="unpublished" class="dropdown-item">Unpublished</button>
                <button type="submit" name="state" value="draft" class="dropdown-item">Draft</button>
            </div>
        </div>
    </span> -->

    <span class="ql-formats">
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline d-none d-md-block"></button>
        <button class="ql-strike d-none d-md-block"></button>
        <button class="ql-script d-none d-md-block" value="sub"></button>
        <button class="ql-script d-none d-md-block" value="super"></button>
        <button class="ql-link"></button>
    </span>

    <span class="ql-formats d-none d-md-inline-block">
        <select class="ql-color"></select>
        <select class="ql-background"></select>
    </span>

    <span class="ql-formats">
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
    </span>

    <span class="ql-formats d-none d-md-inline-block">
        <button class="ql-align" value=""></button>
        <button class="ql-align" value="center"></button>
        <button class="ql-align" value="right"></button>
        <button class="ql-align" value="justify"></button>
    </span>

    <span class="ql-formats">
        <select class="ql-header">
            <option selected="selected">Обычный</option>
            <option value="4">Заголовок 4</option>
            <option value="3">Заголовок 3</option>
            <option value="2">Заголовок 2</option>
        </select>
    </span>

    <span class="ql-formats">
        <button class="ql-clean"></button>
    </span>

    <div id="sub-toolbar">
        <!-- <span class="ql-formats">
            <button class="ql-save" title="Ctrl+S"><i class="fa fa-floppy-o"></i></button>
        </span> -->

        <span class="ql-formats">
            <button class="ql-blockquote"></button>
            <button class="ql-code-block"></button>
        </span>

        <!-- <span class="ql-formats">
            <button class="ql-image" :disabled="!$parent.$props.attachment_id"></button>
            <button class="ql-video"></button>
            <button class="ql-formula"></button>
            </span>

        <span class="ql-formats">
            <button class="ql-table"><i class="fa fa-table"></i></button>
            <button class="ql-columns"><i class="fa fa-columns"></i></button>
        </span> -->

        <span class="ql-formats">
            <select class="ql-shortcodes" ref="ql-shortcodes">
                <option value="[[app_url]]">{{ trans('app_url') }}</option>
                <option value="[[organization]]">{{ trans('organization') }}</option>
                <option value="[[address]]">{{ trans('address') }}</option>
                <option value="[[contact_telephone]]">{{ trans('contact_telephone') }}</option>
                <option value="[[contact_email]]">{{ trans('contact_email') }}</option>
            </select>
        </span>
    </div>
</div>
</template>

<script type="text/ecmascript-6">

export default {
    components: {
        //
    },

    props: {
        //
    },

    data() {
        return {
            //
        };
    },

    created() {
        //
    },

    mounted() {
        this.$nextTick(() => {
            setTimeout(() => {
                var items = document.querySelectorAll('.ql-shortcodes .ql-picker-options .ql-picker-item');
                for (var i = 0; i < items.length; i++) {
                    items[i].dataset.label = this.$refs['ql-shortcodes'][i].text
                }
            }, 100)
        })
    },

    methods: {
        //
    }
}
</script>

<!-- Not used `scoped` attribute -->
<style lang="scss">
.ql-custom .ql-picker-item:before {
    content: attr(data-label);
}

.ql-toolbar.ql-snow {
    background-color: #f5f8fa;
    position: sticky;
    top: 0;
    z-index: 1040;
}

@media (min-width: 768px) {
    .ql-toolbar.ql-snow {
        top: 54px;
    }
}

.ql-container.ql-snow input.ql-image[type=file] {
    display: none;
}

.ql-toolbar.ql-snow .ql-formats {
    margin-right: -2px;
    border-right: 1px solid #ccc;
}

.ql-toolbar.ql-snow .ql-formats:last-of-type {
    margin-right: 0;
    border-right: none;
}

.ql-toolbar.ql-snow .ql-picker-label {
    margin-right: 18px;
}

.ql-snow .ql-color-picker .ql-picker-label,
.ql-snow .ql-icon-picker .ql-picker-label {
    padding: 0 4px;
}

.ql-snow.ql-toolbar button,
.ql-snow .ql-toolbar button {
    width: auto;
}

#sub-toolbar {
    padding-top: 8px;
    margin-top: 8px;
    border-top: 1px solid #ccc;
}

#sub-toolbar button {
    width: auto;
    color: #444;
    line-height: 1;
}

#sub-toolbar button:hover {
    color: #06c;
}

.ql-snow .ql-picker.ql-header {
    width: auto;
}

/**
 * Custom buttons.
 */

.ql-snow .ql-picker.ql-shortcodes .ql-picker-item:before {
    content: attr(data-label);
}

.ql-snow .ql-picker.ql-shortcodes .ql-picker-label::before {
    content: '[[Shortcodes]]'
}
</style>
