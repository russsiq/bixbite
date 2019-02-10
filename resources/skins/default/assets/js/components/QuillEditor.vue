<template>
<div class="quill-editor">
    <toolbar></toolbar>
    <div ref="editor"></div>
</div>
</template>

<script>
import Quill from 'quill';
import Parchment from 'parchment';

import Toolbar from './editor/Toolbar';
import FigureBlot from './editor/FigureBlot.js';

export default {
    components: {
        'toolbar': Toolbar,
    },

    props: {
        lang: {
            /*type: Object,
            required: false*/
        },
        file_url: {
            String,
            // required: true
        },
        attachment_id: {
            Number,
            required: false,
            default: 0
        },
        attachment_type: {
            String,
            required: false,
            default: null
        },
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

            this.handleClicksInsideEditor()
        })
    },

    methods: {
        /**
         * Create an instance of the editor.
         */
        createEditor() {
            Quill.register(FigureBlot, true);

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
                            link(value) {
                                if (value) {
                                    var href = prompt('Enter the URL');
                                    this.quill.format('link', href);
                                } else {
                                    this.quill.format('link', false);
                                }
                            },
                            'emailVars': this.emailVars,

                            placeholder(value) {
                                if (value) {
                                    const cursorPosition = this.quill.getSelection().index;
                                    this.quill.insertText(cursorPosition, value);
                                    this.quill.setSelection(cursorPosition + value.length);
                                }
                            },
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

            /*var customButton = document.querySelector('#custom-button');
            if (customButton) {
                customButton.addEventListener('click', function() {
                    console.log('Clicked!');
                });
            }*/

            // Handlers can also be added post initialization
            quill.getModule('toolbar').addHandler('image', this.imageHandler)

            return quill
        },

        imageHandler() {
            if (!this.attachment_id) {
                Notification.warning({
                    message: 'Before you can insert images, you must save the article.'
                })

                return false;
            }

            let fileInput = this.editor.container.querySelector('input.ql-image[type=file]');

            if (fileInput == null) {
                fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('accept', 'image/*');
                fileInput.classList.add('ql-image');
                fileInput.addEventListener('change', () => {
                    const files = fileInput.files;

                    if (!files || !files.length) {
                        Notification.error({
                            message: 'No files selected'
                        })
                        return false;
                    }

                    const formData = new FormData();
                    formData.append('file', files[0]);

                    if (this.attachment_id > 0) {
                        formData.append('attachment_id', this.attachment_id);
                        formData.append('attachment_type', 'articles');
                    }

                    this.editor.enable(false);

                    axios
                        .post(this.$props.file_url + '/upload', formData)
                        .then(response => {
                            // Save current cursor state.
                            let range = this.editor.getSelection(true);

                            this.editor.enable(true)
                            // this.editor.insertText(range.index, '\n', Quill.sources.USER)
                            this.editor.insertEmbed(range.index, 'figure-image', {
                                url: response.data.file.url,
                                caption: response.data.file.title,
                            }, Quill.sources.USER)
                            this.editor.setSelection(range.index + 1, Quill.sources.SILENT)

                            fileInput.value = '';
                        })
                        .catch(error => {
                            Notification.error({
                                message: 'quill image upload failed'
                            })
                            console.log(error);
                            this.editor.enable(true);
                        });
                });

                this.editor.container.appendChild(fileInput);
            }

            fileInput.click();
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

        /**
         * Handle click events inside the editor.
         */
        handleClicksInsideEditor() {
            this.editor.root.addEventListener('click', (ev) => {
                let blot = Parchment.find(ev.target, true);
                console.log(blot)
            });
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

.single_article__image {
    position: relative;
    cursor:pointer;
}

.single_article__image:hover:after {
    box-shadow: inset 0 0 10px 0 #008cba;
    content: '';
    display: block;
    height: 100%;
    position: absolute;
    top: 0;
    width: 100%;
}

.single_article_image__caption {
    text-align: right;
    background: #e9ecef;
    padding: 0.25rem 1rem;
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
