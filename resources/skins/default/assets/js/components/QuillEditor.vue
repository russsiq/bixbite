<template>
<div class="quill-editor">
    <div ref="editor"></div>
</div>
</template>

<script>
import Quill from 'quill';

export default {
    components: {
        
    },

    props: {
        articleId: {
            type: Number,
            default: 0
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
            return new Quill(this.$refs.editor, {
                modules: {
                    //syntax: true,
                    toolbar: [
                        [{
                            header: [2, 3, 4, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }, 'link'],
                        ['blockquote', 'code-block'],
                        ['clean'],
                    ]
                },
                theme: 'bubble', // bubble or snow
                scrollingContainer: 'html, body',
                placeholder: "Write to the world..."
                //formats: ['bold', 'underline', 'header', 'italic']
            });
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

.ql-editor {
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0;
    color: #888;
    display: block;
    font-size: 1rem;
    line-height: 1.6;
    padding: 0.375rem 0.5rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    width: 100%;
    min-height: 388px;
    font-family: "Bookman old style";
}

.ql-editor:focus {
    background-color: #fff;
    border-color: #3bceff;
    box-shadow: none;
    color: #000;
    outline: 0;
}

.ql-editor p {
    margin-bottom: 1rem;
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
