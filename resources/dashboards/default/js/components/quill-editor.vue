<template>
<div class="quill-editor">
    <toolbar></toolbar>
    <div ref="editor"></div>

    <quill-fragment v-bind="quillFragment" @close="closePromptFragmentContent"></quill-fragment>
</div>
</template>

<script type="text/ecmascript-6">
import Quill from 'quill';
import Parchment from 'parchment';

import Toolbar from './editor/toolbar';
import QuillFragment from './editor/quill-fragment.vue';
import FragmentBlot from './editor/blots/FragmentBlot.js';

import FigureBlot from './editor/blots/FigureBlot';
import figureHandler from './editor/handlers/figureHandler';

export default {
    name: 'quill-editor',

    components: {
        'toolbar': Toolbar,
        'quill-fragment': QuillFragment,
    },

    props: {
        value: {
            type: String,
            required: true
        },

        attachable: {
            type: Object,
            required: true,
            validator(attachable) {
                return 'number' === typeof attachable.id
                    && 'string' === typeof attachable.type;
            }
        },
    },

    data() {
        return {
            editor: null,

            quillFragment: {
                quill: null,
                blot: null,
                promptContent: false,
            },
        };
    },

    computed: {
        cursorPosition() {
            return this.editor.getSelection().index;
        },
    },

    watch: {
        editor(editor, nullable) {
            // Передаем экземпляр Quill-редактора дочерним компонентам.
            this.quillFragment.quill = editor;
        }
    },

    async mounted() {
        await this.loadFromJsonPath('attachments');
        await this.loadFromJsonPath('articles');

        this.$refs.editor.innerHTML = this.$props.value.trim();

        // Executed after the next DOM update cycle.
        this.$nextTick(() => {
            this.editor = this.createEditor();
            this.handleClicksInsideEditor();
        });
    },

    methods: {
        /**
         * Create an instance of the editor.
         */
        createEditor() {
            /*FigureBlot.remove = function() {
                alert('Image was removed!')
            }*/

            Quill.register(FigureBlot, true);
            Quill.register(FragmentBlot, true);

            const icons = Quill.import('ui/icons');
            icons.header[3] = require('!html-loader!quill/assets/icons/header-3.svg');
            icons.header[4] = require('!html-loader!quill/assets/icons/header-4.svg');

            const quill = new Quill(this.$refs.editor, {
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
                            'fragment': this.queryFragmentContent,
                            // 'shortcodes': this.insertShortcode,
                            'save': this.saveFromEditor,

                            published() {

                            }
                        }
                    },
                },
                theme: 'snow', // bubble or snow
                scrollingContainer: 'html, body',
                placeholder: 'Начните печатать текст записи ...'
            });

            quill.on('text-change', this.updateEditorValue);

            // Handlers can also be added post initialization.
            quill.getModule('toolbar')
                .addHandler('image', () => {
                    figureHandler(quill, this.$props.attachable);
                });

            return quill;
        },

        /**
         * Update the editor value.
         */
        updateEditorValue(delta, old, source) {
            this.$emit(
                'input',
                this.editor.getText() ? this.editor.root.innerHTML : ''
            );
        },

        /**
         * Handle click events inside the editor.
         */
        handleClicksInsideEditor() {
            this.editor.root.addEventListener('click', (event) => {
                const blot = Parchment.find(event.target, true);

                if (blot instanceof FragmentBlot) {
                    this.quillFragment = {
                        ...this.quillFragment,
                        blot: blot,
                        promptContent: true
                    }
                }
            });
        },

        /**
         * Запросить содержимое фрагмента.
         */
        queryFragmentContent() {
            this.quillFragment = {
                ...this.quillFragment,
                blot: null,
                promptContent: true
            }
        },

        /**
         * Закрыть окно получения фрагмента.
         * @param  {object} quillFragment
         */
        closePromptFragmentContent(quillFragment) {
            this.quillFragment = {
                ...this.quillFragment,
                ...quillFragment
            }
        },

        // insertShortcode(value) {
        //     if (value) {
        //         const position = this.cursorPosition;

        //         this.editor.insertText(position, value);
        //         this.editor.setSelection(position, value.length);
        //     }
        // },

        saveFromEditor(value) {
            const data = JSON.parse(value);

            data && this.$emit('json', data);
        },
    }
}
</script>

<!-- Not used `scoped` attribute -->
<style lang="scss">
.quill-editor {
    background: #aaa;
    padding-bottom: 0.1rem;
}

.ql-container {
    font-family: inherit;
}

.ql-container.ql-snow {
    border: none;
    background: #fafafa;
    margin: 1.2rem auto;
    padding: 0;
    width: 100%;
    max-width: 888px;
}

.ql-snow .ql-editor {
    position: relative;
    display: block;
    margin: 0 auto;
    padding: 1.2rem 1.6rem;
    width: 100%;
    min-height: 688px;
    max-width: 888px;

    font-family: "Bookman old style";
    font-size: 16px;
    line-height: 1.4;
    letter-spacing: -.4px;
    word-wrap: break-word;

    color: #888;
    box-shadow: 0 0 35px #000;
    border: 1px solid #888;
    border-top: 1px solid #dedede;
    border-radius: 0;
    transition: color 0.15s ease-in-out;
}

.ql-snow .ql-editor:focus {
    outline: 0;
    color: inherit;
    transition: color 0.15s ease-in-out;
}

.ql-snow .ql-editor.ql-blank::before {
    left: 1.6rem;
    padding: 0;
}

.ql-snow .ql-editor .ql-video,
.ql-snow .ql-editor blockquote,
.ql-snow .ql-editor h1,
.ql-snow .ql-editor h2,
.ql-snow .ql-editor h3,
.ql-snow .ql-editor h4,
.ql-snow .ql-editor h5,
.ql-snow .ql-editor h6,
.ql-snow .ql-editor ol,
.ql-snow .ql-editor p,
.ql-snow .ql-editor pre,
.ql-snow .ql-editor ul {
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
    cursor: pointer;
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


<!-- Вариант с декодированием изображения

var img = {
    'img' : value
}
$.ajax({
        url: "../Writers/writerCertificateupload",
        data: img,
        contentType: undefined,
        type: 'post'
}).success(function(path){
    node.setAttribute('src', path);
})

public function writerCertificateupload()//pending
{
    $id = $this->Auth->user('id');
    $this->autoRender = false;
    define('UPLOAD_DIR', 'files/post/');
    $img = $_POST['img'];
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $file = UPLOAD_DIR . uniqid() . '.jpg';
    $success = file_put_contents($file, $data);

    $file = "../".$file;
    print $success ? $file : 'Unable to save the file.';
} -->
