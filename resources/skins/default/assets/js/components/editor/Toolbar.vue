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
            <option value="2"></option>
            <option value="3"></option>
            <option value="4"></option>
            <option value=""></option>
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
            <select class="ql-shortcodes">
                <option value="[[app_url]]">Домашняя страница</option>
                <option value="[[organization]]">Название организации</option>
                <option value="[[address]]">Адрес организации</option>
                <option value="[[contact_telephone]]">Контактный телефон</option>
                <option value="[[contact_email]]">Контактный email</option>
            </select>
        </span>
    </div>
</div>
</template>

<script>
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
        //
    },

    methods: {
        //
    }
}
</script>

<style>
.ql-custom .ql-picker-item:before {
    content: attr(data-label);
}

.ql-toolbar.ql-snow {
    background-color: #f5f8fa;
    position: sticky;
    /* top: 54px;
    z-index: 1020; */
    top: 0;
    z-index: 1050;
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

/**
 * Custom buttons.
 */

.ql-picker.ql-shortcodes .ql-picker-item:before {
    content: attr(data-label);
}

.ql-picker.ql-shortcodes .ql-picker-label::before {
    content: '[[Shortcodes]]'
}
</style>
