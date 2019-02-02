<template>
    <div ref="editor"></div>
</template>

<script>
    import Quill from 'quill';

    export default {
        props: {
            articleId: {
                type: Number,
                default: 0
            }
        },

        data() {
            return {
                editor: null,
                textarea: null
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
                            [{ header: [2, 3, 4, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{'list': 'ordered'}, {'list': 'bullet'}, 'link'],
                            ['blockquote', 'code-block'],
                            ['clean'],
                        ]
                    },
                    theme: 'bubble',  // bubble or snow
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
            }
        }
    }
</script>

<style>
    .ql-container {
        font-family: inherit;
    }
    .ql-editor {
        display: block;
        width: 100%;
        padding: 0.375rem 0.5rem;
        line-height: 1.6;
        color: #888;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ccc;
        border-radius: 0;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;

        font-size: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .ql-editor:focus {
        color: #444;
        background-color: #fff;
        border-color: #3bceff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 140, 186, 0.25);
    }
    .ql-editor p {
        margin-bottom: 1rem;
    }
    .ql-bubble .ql-tooltip {
        z-index: 1;
    }
</style>
