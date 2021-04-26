<template>
<div class="">
    <link :href="dashboard('css/code-editor.css')" rel="stylesheet" />

    <nav class="navbar navbar-expand navbar-dark bg-primary justify-content-between">
        <a href="#" class="navbar-brand">{{ theme }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-navbar"><span class="navbar-toggler-icon"></span></button>
        <div id="bs-navbar" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link" title="Создать шаблон" @click.prevent="createTemplate"><i class="fa fa-plus"></i></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#hotkeys"><i class="fa fa-leanpub"></i></a>
                </li>
                <!-- <li class="nav-item">
                    <a href="#" title="Fullscreen (F11)" @click.prevent="editor.setOption('fullScreen', true)" class="nav-link"><i class="fa fa-arrows-alt"></i></a>
                </li> -->
            </ul>
        </div>
    </nav>

    <!-- Main content form -->
    <form method="post" @submit.prevent="">
        <div class="container-fluid">
            <div class="row">
                <nav class="col-md-3 bg-light sidebar" @click.prevent="chooseTemplate">
                    <ul class="tree_view__list">
                        <templates-tree v-for="(item, index) in templates" :item="item" :key="item.id"></templates-tree>
                    </ul>
                </nav>

                <main class="col-md-9 ml-sm-auto p-3 border-bottom border-right">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" :value="template && template.filename" readonly class="form-control" placeholder="Выберите шаблон для редактирования ..." autocomplete="off" required />
                        </div>
                    </div>

                    <div class="mb-3 border">
                        <textarea ref="codeEditor" value="" rows="12" class="form-control"></textarea>

                        <div v-if="template && template.exists" class="status d-flex" style="background-color: #f7f7f7; border-top: 1px solid #ddd;">
                            <small class="template__title p-2"><span class="text-muted">Дата изменения:</span> {{ template.modified }}</small>
                            <small class="template__title p-2 ms-auto"><span class="text-muted">Размер:</span> {{ template.size }}</small>
                        </div>
                    </div>

                    <div v-if="template && template.exists" class="d-flex">
                        <template>
                            <button type="button" @click.prevent="updateTemplate" title="Ctrl+S" class="btn btn-outline-success">
                                <span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
                                <span class="d-none d-md-inline">Сохранить</span>
                            </button>
                        </template>
                    </div>
                </main>
            </div>
        </div>
    </form>

    <div id="hotkeys" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Справка</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <h5>Сочетания клавиш codemirror</h5>
                    <div class="row">
                        <div class="col col-xs-6">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td><kbd>Ctrl + S</kbd></td>
                                        <td>сохранить шаблон</td>
                                    </tr>
                                    <tr>
                                        <td><kbd>F11</kbd></td>
                                        <td>полноэкранный режим</td>
                                    </tr>
                                    <tr>
                                        <td><kbd>Ctrl+E</kbd></td>
                                        <td>развернуть аббревиатуру (<a href="http://docs.emmet.io/cheat-sheet/" target="_blank" title="Emmet cheat sheet" class="">emmet</a>)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col col-xs-6">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td><kbd>Ctrl + F</kbd></td>
                                        <td>начать поиск</td>
                                    </tr>
                                    <tr>
                                        <td><kbd>Ctrl + G</kbd></td>
                                        <td>найти далее</td>
                                    </tr>
                                    <tr>
                                        <td><kbd>Shift + Ctrl + F</kbd></td>
                                        <td>заменить</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script type="text/ecmascript-6">
import CodeMirror from 'codemirror';
import emmet from '@emmetio/codemirror-plugin';

import 'codemirror/mode/htmlmixed/htmlmixed.js';
import 'codemirror/mode/css/css.js';
import 'codemirror/mode/javascript/javascript.js';
import 'codemirror/mode/php/php.js';

import 'codemirror/addon/display/fullscreen.js';
import 'codemirror/addon/dialog/dialog.js';
import 'codemirror/addon/search/search.js';
import 'codemirror/addon/search/searchcursor.js';
import 'codemirror/addon/selection/active-line.js';

import {
    mapGetters
} from 'vuex';

import TemplatesTree from '@/views/templates/partials/templates-tree';

// Синтаксисы для подсветки языков.
const CM_MODES = {
    css: 'text/x-gss',
    js: 'javascript',
    php: 'php',
    default: 'text/html'
};

// Настройки для редактора кода.
const CM_OPTIONS = {
    lineNumbers: true,
    lineWrapping: true,
    styleActiveLine: true,
    indentUnit: 4,
    tabMode: 'shift',
    enterMode: 'indent',
    theme: 'default',
    extraKeys: {
        'Tab': 'indentMore',
        'Shift-Tab': 'indentLess',
        'Ctrl-E': 'emmetExpandAbbreviation',
        // 'Enter': 'emmetInsertLineBreak',
        'F11': function(editor) {
            editor.setOption('fullScreen', !editor.getOption('fullScreen'));
        },
        'Esc': function(editor) {
            editor.getOption('fullScreen') && editor.setOption('fullScreen', false);
        },
    }
};

/**
 * Построить структуру категорий.
 * @param  {array} parts
 * @param  {Map} map
 * @param  {array} folders
 */
const build = function buildParentsTree(parts, map, folders) {
    parts.length && parts.reduce(function(acc, curr, index, arr) {
        const parent_id = acc.length ? hash(acc) : 0;

        acc.push(curr);

        const current_id = hash(acc);

        if (!map.has(current_id)) {
            map.set(current_id);

            folders.push({
                id: current_id,
                name: curr,
                parent: parent_id,
            });
        }

        return acc;
    }, []);
}

/**
 * Создать хэш. Взято из `vue.common.dev.js`.
 * @param  {string} str
 */
const hash = function hashFromVue(str) {
    str = str.toString();
    let hash = 5381;
    let i = str.length;
    while (i) hash = (hash * 33) ^ str.charCodeAt(--i);
    return hash >>> 0
}

export default {
    name: 'templates-index',

    components: {
        'templates-tree': TemplatesTree
    },

    props: {
        model: {
            type: Function,
            required: true
        },
    },

    data() {
        return {
            /**
             * Текущий шаблон.
             * @type {?string}
             */
            template: null,

            /**
             * Имя текущего файла. Данное поле находится в наблюдателе.
             * От этого имени зависит выбор текущего шаблона.
             * @type {?string}
             */
            filename: null,

            /**
             * Экземпляр редактора кода.
             * @type {CodeMirror}
             */
            editor: null,

            /**
             * Плоская коллекия шаблонов. Данное поле находится в наблюдателе.
             * При изменении коллекции меняется древовидная коллекция.
             * @type {array}
             */
            collection: [],

            /**
             * Древовидная коллекция шаблонов.
             * @type {array}
             */
            templates: [],
        }
    },

    computed: {
        ...mapGetters({
            meta: 'meta/all',
        }),

        theme() {
            return this.meta.theme || 'Загрузка шаблонов темы ...';
        },

        tmode() {
            const extension = this.filename ? this.filename.split('.').pop() : 'php';

            return CM_MODES[extension] || CM_MODES['default'];
        },
    },

    watch: {
        filename(newVal, oldVal) {
            newVal && this.onChangeFileName();
        },

        collection:{
            immediate: true,
            deep: true,
            handler: function(templates, oldVal) {
                const folders = [];
                const parents_map = new Map();

                // Создадим дубликат массива объектов, но с необходимым набором свойств.
                const nested = templates.map(function(item, index, arr) {
                    const parts = item.filename.split('\\');
                    const name = parts.pop();
                    const parent = parts.length ? hash(parts) : 0;

                    // Построение структуры категорий выполнять
                    // после удаления имени файла `name`,
                    // чтобы остался только массив категорий.
                    build(parts, parents_map, folders);

                    return {
                        ...item,
                        name,
                        parent,
                    }
                });

                const data = [...folders, ...nested];

                // Добавим дочерние элементы.
                data.forEach(function(item, index, arr) {
                    item.children = arr.filter(subItem => item.id === subItem.parent);
                });

                // Оставим только корневые элементы.
                this.templates = data.filter(item => item.parent === 0);
            }
        }
    },

    async mounted() {
        await this.loadFromJsonPath('templates');

        this.$props.model.$fetch()
            .then(this.fillTree);

        this.initialize();
    },

    beforeDestroy() {
        this.destroy();
    },

    methods: {
        fillTree(templates) {
            this.collection = templates;
        },

        /**
         * Выбрать шаблон.
         */
        chooseTemplate(event) {
            const filename = event.target.dataset.path;

            filename && (this.filename = filename);
        },

        onChangeFileName() {
            this.template = this.collection.find((template) => template.filename === this.filename)

            this.editor.setOption('mode', this.tmode);
            this.editor.setValue(this.template.content);

            // Clears the editor's undo history.
            this.editor.getDoc().clearHistory();

            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        },

        createTemplate() {
            const name = prompt('Задайте имя новому файлу: *.blade.php', 'new');

            name && this.$props.model.$create({
                    data: {
                        filename: name + '.blade.php',
                        content: '',
                    }
                })
                .then((template) => {
                    this.collection.push(template);
                    this.filename = template.filename;
                });
        },

        updateTemplate() {
            this.template && this.template.id && this.$props.model.$update({
                params: {
                    id: this.template.id
                },

                data: {
                    filename: this.template.filename,
                    content: this.template.content
                }
            });
        },

        initialize() {
            emmet(CodeMirror);

            this.editor = CodeMirror.fromTextArea(this.$refs.codeEditor, CM_OPTIONS);

            this.editor.on('change', (editor) => {
                if (this.template.id) {
                    this.template.content = editor.getValue();
                }
            });

            document.addEventListener('keydown', this.onSaveHandler);
        },

        destroy() {
            const element = this.editor.getWrapperElement();
            element && element.remove && element.remove();

            this.editor.off('change');

            document.removeEventListener('keydown', this.onSaveHandler);
        },

        onSaveHandler(event) {
            if (event.ctrlKey && 'S'.charCodeAt(0) === event.keyCode) {
                event.preventDefault();

                this.updateTemplate();
            }
        },
    },
}
</script>
