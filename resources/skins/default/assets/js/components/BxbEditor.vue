<template lang="html">
    <div class="bxb_editor">
        <div class="bxb_panel">
            <div class="bxb_panel__inner">
                <button class="bxb_btn" :title="title(button)" :key="button.cmd"
                    v-for="(button, key, index) in applicableCommands"
                    @click="doCommand(button, $event)"
                    ><i :class="icon(button)"></i></button>
                <span class="bxb_vr"></span>
            </div>
        </div>
        <div class="bxb_content">
            <div id="editor" class="bxb_content__item" contenteditable="true" @input="update">
                <slot><p></p></slot>
            </div>
        </div>
        <textarea name="content" class="form-control d-none" rows="4">{{ content }}</textarea>
    </div>
</template>

<script>

import availableCommands from './json/available-commands.json'

export default {
    props: {
        lang: {String, default: 'en'}
    },
    data() {
        return {
            content: null,
            
            // All commands to document.execCommand.
            availableCommands: availableCommands,
            
            // Commands that are supported by the browser.
            supportedCommands: [],
            
            // Commands specified by config.
            specifiedCommands: ['bold','italic','underline','strikeThrough','subscript'],
            
            // Attributes to delete from html content.
            removableAttributes: ['id', 'style', 'class'],
            
            blocks: [
                {type: 'paragraph', html: '<p>В любви обычно несчастен (так и хочется сказать - потому что верит в романтику), потому что терпеть постоянно устраиваемые Близнецом феерии идиотического веселья и веселого идиотизма в силах только Овен, а на всех Близнецов ГОвна не хватает.</p>'},
                {type: '', html: ''},
            ]
        }
    },
    mounted() {
        // Executed after the next DOM update cycle.
        this.$nextTick(() => {
            let source = document.getElementById('editor')
            source.innerHTML = source.innerHTML.replace(/\n/g, ' ')
            this.update(source)
        })
    },
    computed: {
        // Get commands to be used in the editor.
        applicableCommands: function () {
            return this.availableCommands.filter((command, i) => {
                if (this.supported(command)) { // && this.specifiedCommands.includes(command.cmd)) {
                    this.supportedCommands[command.cmd] = command
                    
                    return command
                }
            })
        },
    },
    methods: {
        update: function(event) {
            //this.$emit('update', event.target.innerText);
            
            let self = this
            let removable = this.removableAttributes || []
            let updated = event.innerHTML || event.target.innerHTML
            let wrapper = document.createElement('div');
            
            // updated = updated.replace(/<([^ >]+)[^>]*>/ig, '<$1>')
            // updated = updated.replace(/<[^\/>]+>[ \n\r\t]*<\/[^>]+>/ig, '')
            
            wrapper.innerHTML = updated;
            this.walkByDom(wrapper, (element) => {
                self.removeAttribute(element, removable)
            })
            
            this.content = wrapper.innerHTML
        },
        doCommand: function (cmd, event) {
            if (event) event.preventDefault()
            
            let command = this.supportedCommands[cmd.cmd];
            
            let val = (typeof command.val !== "undefined") ? prompt("Value for " + command.cmd + "?", command.val) : ''
            document.execCommand(command.cmd, false, (val || ''))
            // https://codepen.io/chrisdavidmills/pen/gzYjag
            // https://developer.mozilla.org/en-US/docs/Web/API/document/execCommand
        },
        walkByDom: function (root, func) {
            for (let node of root.childNodes) func(node)
        },
        icon: function (command) {
            return (typeof command.icon !== 'undefined') ? 'fa fa-' + command.icon : command.cmd;
        },
        title: function (command) {
            return (typeof command.desc[this.lang] !== 'undefined') ? command.desc[this.lang] : command.cmd;
        },
        supported: function (command) {
            return !!document.queryCommandSupported(command.cmd) ? true : false
        },
        removeAttribute: function(element, attribute) {
            if (! Array.isArray(attribute)) attribute = [attribute]
            
            Object.keys(attribute).forEach((key) => {
                if (element.removeAttribute) {
                    element.removeAttribute(attribute[key])
                }
            })
        }
    }
}
// @click="update" 'IMG' == event.target.tagName
// @keyup.enter="tagging('formatBlock', $event, 'p')"
</script>

<style>
    .bxb_editor {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0;
    }
    
    /* Panel */
    .bxb_panel {
        margin-bottom: 0;
        background-color: rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
    .bxb_panel__inner {
        padding: .35rem ;
        background-color: transparent;
    }
    .bxb_btn {
        display: inline-block;
        padding: .25rem .35rem;
        border: 1px solid transparent;
        border-radius: 2px;
        background: transparent;
        cursor: pointer;
        line-height: 1;
        font-size: 14px;
        opacity: .6;
        transition: opacity .4s
    }
    .bxb_btn:hover {
        border-color: #3bceff;
        opacity: 1;
        transition: opacity .2s
    }
    .bxb_vr {
        border-left: 1px solid #3bceff;
        width: 0;
        margin-right: .15rem;
        opacity: .4;
        padding: 0;
    }
    
    
    /* Content */
    /*.bxb_content {
        max-height: 380px;
        overflow-y: scroll;
    }*/
    .bxb_content__item {
        color: #444;
        background-color: #fff;
        border: 1px solid transparent;
        padding: 0.75rem 1.25rem;
        max-height: 480px;
        overflow-y: scroll;
    }
    .bxb_content__item:focus {
        color: #222;
        background-color: #fff;
        border-color: #3bceff;
        outline: 0;
    }
</style>
