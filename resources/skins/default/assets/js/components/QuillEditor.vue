<template>
<div class="quill-editor">
    <toolbar></toolbar>
    <div ref="editor"></div>
</div>
</template>

<script>
import Quill from 'quill';
import Toolbar from './editor/Toolbar';

export default {
    components: {
        'toolbar': Toolbar,
    },

    props: {
        articleId: {
            type: Number,
            default: 0
        },
        lang: {
            /*type: Object,
            required: false*/
        }
    },

    data() {
        return {
            editor: null,
            textarea: null,
            seoModalShown: false,

            form: {
                meta: {
                    description: '',
                    keywords: ''
                }
            }
        };
    },

    mounted() {
        // Executed after the next DOM update cycle.
        this.$nextTick(() => {
            this.textarea = document.querySelector('[name=content]')
            this.editor = this.createEditor()
            this.handleEditorValue(this.textarea.value.trim())
        })
    },

    methods: {
        /**
         * Create an instance of the editor.
         */
        createEditor() {
            const icons = Quill.import('ui/icons')
            icons.header[3] = require('!html-loader!quill/assets/icons/header-3.svg')
            icons.header[4] = require('!html-loader!quill/assets/icons/header-4.svg')

            let quill = new Quill(this.$refs.editor, {
                modules: {
                    //syntax: true,
                    toolbar: {
                        // Selector for toolbar container.
                        container: '#toolbar-container',
                        handlers: {
                            //'bold': customBoldHandler
                            'link': function(value) {
                                if (value) {
                                    var href = prompt('Enter the URL');
                                    this.quill.format('link', href);
                                } else {
                                    this.quill.format('link', false);
                                }
                            },
                            "placeholder": function (value) {
                                if (value) {
                                    const cursorPosition = this.quill.getSelection().index;
                                    this.quill.insertText(cursorPosition, value);
                                    this.quill.setSelection(cursorPosition + value.length);
                                }
                            }
                        }
                    },
                },
                theme: 'snow', // bubble or snow
                placeholder: "Write to the world..."
            })

            /*const placeholderPickerItems = Array.prototype.slice.call(
                document.querySelectorAll('.ql-custom .ql-picker-item')
            );

            placeholderPickerItems.forEach(item => item.textContent = item.dataset.value);

            document.querySelector('.ql-custom .ql-picker-label').innerHTML
                = 'Insert placeholder' + document.querySelector('.ql-custom .ql-picker-label').innerHTML;*/

            var customButton = document.querySelector('#custom-button');
            if (customButton) {
                customButton.addEventListener('click', function() {
                    console.log('Clicked!');
                });
            }

            // Handlers can also be added post initialization
            /*var toolbar = quill.getModule('toolbar')
            toolbar.addHandler('image', this.showImageUI())*/

            return quill
        },

        showImageUI() {
            alert('Hi')
        },

        /**
         * Handle the editor value.
         */
        handleEditorValue(value) {
            this.editor.root.innerHTML = value
            this.editor.on('text-change', () => this.updateEditorValue(
                this.editor.getText() ? this.editor.root.innerHTML : ''
            ))
        },

        /**
         * Update the editor value.
         */
        updateEditorValue(content) {
            this.$emit('input', content)
            this.textarea.value = content
        },
    }
}
</script>

<style>
.ql-container {
    font-family: inherit;
}

.ql-snow .ql-editor {
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid transparent;
    border-radius: 0;
    color: #888;
    display: block;
    font-size: 16px;
    line-height: 1.6;
    padding: 0.375rem 0.5rem;
    transition: border-color 0.15s ease-in-out, background-color 0.15s ease-in-out;
    width: 100%;
    min-height: 388px;
    font-family: "Bookman old style";
}

.ql-snow .ql-editor:focus {
    background-color: #fff;
    border: 1px solid #3bceff;
    box-shadow: none;
    color: #000;
    outline: 0;
}

.ql-snow .ql-editor p,
.ql-snow .ql-editor ol,
.ql-snow .ql-editor ul,
.ql-snow .ql-editor pre,
.ql-snow .ql-editor blockquote,
.ql-snow .ql-editor .ql-video,
.ql-snow .ql-editor h1,
.ql-snow .ql-editor h2,
.ql-snow .ql-editor h3,
.ql-snow .ql-editor h4,
.ql-snow .ql-editor h5,
.ql-snow .ql-editor h6 {
    margin-bottom: 1rem;
}

.ql-snow .ql-editor h1 {
    font-size: 2.25em;
}

.ql-snow .ql-editor h2 {
    font-size: 1.8rem;
}

.ql-snow .ql-editor h3 {
    font-size: 1.575rem;
}

.ql-snow .ql-editor h4 {
    font-size: 1.35rem;
}

.ql-snow .ql-editor h5 {
    font-size: 1.125rem;
}

.ql-snow .ql-editor h6 {
    font-size: 0.9rem;
}


.ql-bubble .ql-tooltip {
    z-index: 1;
}
</style>

<style>
/*.form-control {
    border: none;
}
.ql-editor {
    border: none!important;
}
body {
    background: none;
}*/
</style>
