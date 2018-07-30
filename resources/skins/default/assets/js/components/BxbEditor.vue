<template lang="html">
    <div class="bxb_editor">
        
        <div class="bxb_panel">
            <div class="bxb_panel__inner">
                <button class="bxb_btn" @click="viewSource.toggle($event)"><i class="fa fa-code"></i></button>
                <button class="bxb_btn" @click="onInsert(2, $event)"><i class="fa fa-plus"></i></button>
                <button class="bxb_btn" :title="title(button)" :key="button.cmd"
                    v-for="(button, key, index) in applicableCommands"
                    @click="doCommand(button, $event)"
                    ><i :class="icon(button)"></i></button>
                <span class="bxb_vr"></span>
            </div>
        </div>
        <div class="bxb_content" :style="{ display: viewSource.block }">
            <div class="bxb_content__item"
                contenteditable="true"
                :index="index"
                :key="index"
                v-for="(block, index) in blocks"
                v-html="block.outerHTML"
                @input="update(index, $event)"
                @blur="onBlur(index, $event)"
                @focus="/*onFocus(index, $event)*/"
                @keyup.delete="onTrim(index, $event)"
                @contextmenu="/*openMenu*/"
                @dblclick="openMenu"
                ></div>
            
        </div>
        
        <div id="right-click-menu" tabindex="-1" ref="right" v-if="viewMenu" :style="{top:menuTop, left:menuLeft}">
            <button class="bxb_btn" @click="doCommand({cmd: 'bold'}, $event)"><i class="fa fa-bold"></i></button>
            <button class="bxb_btn" @click="doCommand({cmd: 'italic'}, $event)"><i class="fa fa-italic"></i></button>
            <button class="bxb_btn" @click="doCommand({cmd: 'underline'}, $event)"><i class="fa fa-underline"></i></button>
            <button class="bxb_btn" @click="doCommand({cmd: 'strikeThrough'}, $event)"><i class="fa fa-strikethrough"></i></button>
        </div>
        
        <div id="content" :style="{ display: viewSource.none }"><slot></slot></div>
    </div>
</template>

<script>

import availableCommands from './json/available-commands.json'
import CleanWordHTML from './js/clean-word.js'
import Display from './js/display.js'

// Vue.use(CleanWordHTML)

export default {
    props: {
        lang: {String, default: 'en'}
    },
    data() {
        return {
            viewSource: new Display,
            content: null,
            source: null,
            blocks: [],
            
            // Menu
            viewMenu: false,
            menuTop: '0px',
            menuLeft: '0px',
            
            // All commands to document.execCommand.
            availableCommands: availableCommands,
            
            // Commands that are supported by the browser.
            supportedCommands: [],
            
            // Commands specified by config.
            specifiedCommands: ['bold','italic','underline','strikeThrough','subscript']
        }
    },
    mounted() {
        // Executed after the next DOM update cycle.
        this.$nextTick(() => {
            let content = document.getElementById('content') 
            let source = content.firstChild
            source.value = this.cleanString(source.value)
            this.content = source
            
            this.parsingBlocks(source.value)
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
        parsingBlocks: function(html) {
            for (let node of (this.wrapElements(html)).children) {
                this.blocks.push(node)
            }
        },
        insertBlock: function(index, event) {
            this.blocks.splice(index, 0,
                (this.wrapElements(
                    '<p></p>'
                )).firstChild)
        },
        replaceBlock: function(index, event) {
            this.blocks.splice(index, 1,
                (this.wrapElements(
                    this.cleanString(event.target.innerHTML)
                )).firstChild
            )
        },
        deleteBlock: function(index) {
            this.blocks.splice(index, 1)
        },
        onInsert: function(index, event) {
            if (event) event.preventDefault()
            
            this.insertBlock(index, event)
        },
        onBlur: function(index, event) {
            for (let i = 0; i < event.target.children.length; i++) {
                this.blocks.splice(
                    index + i,
                    0 == i ? 1 : 0,
                    0 == i ? event.target.firstChild : event.target.children[i]
                )
            }
            this.viewMenu = false
        },
        onFocus: function(index, event) {
            console.log(index)
            if (event) event.preventDefault()
            
            // console.log(event)
            // this.setMenu(event)
        },
        onTrim: function(index, event) {
            if ('' == event.target.innerHTML) {
                this.deleteBlock(index)
            }
        },
        update: function(index, event) {
            let html = ''
            
            this.$emit('update', event.target.innerHTML)
            
            Object.keys(this.blocks).forEach((key) => {
                if (index == key) {
                    html += event.target.innerHTML
                } else {
                    html += this.blocks[key].outerHTML
                }
            })
            
            let content = document.getElementById('content') 
            let source = content.firstChild
            source.value = this.cleanString(html)
            this.content = source
        },
        doCommand: function(cmd, event) {
            // https://codepen.io/chrisdavidmills/pen/gzYjag
            // https://developer.mozilla.org/en-US/docs/Web/API/document/execCommand
            
            if (event) event.preventDefault()
            
            let command = this.supportedCommands[cmd.cmd];
            let val = (typeof command.val !== "undefined") ? prompt("Value for " + command.cmd + "?", command.val) : ''
            
            document.execCommand(command.cmd, false, (val || ''))
            this.viewMenu = false
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
        cleanString: function(string) {
            string = string.replace(/\n/g, ' ')
            string = string.replace(/>\s*</g, '><')
            
            return (new CleanWordHTML(string)).string
        },
        wrapElements: function(html) {
            return document.createRange().createContextualFragment(html)
        },

        // Menu
        setMenu: function(e) {
            let top = e.y
            let left = e.x
            let largestHeight = window.innerHeight - this.$refs.right.offsetHeight - 25;
            let largestWidth = window.innerWidth - this.$refs.right.offsetWidth - 25;

            if (top > largestHeight) top = largestHeight;
            if (left > largestWidth) left = largestWidth;
            
            this.menuTop = top + 'px';
            this.menuLeft = left + 'px';
            this.viewMenu = true
        },
        closeMenu: function() {
            this.viewMenu = false;
        },
        openMenu: function(e) {
            this.viewMenu = true;
            Vue.nextTick(function() {
                this.$refs.right.focus();
                this.setMenu(e)
            }.bind(this));
            e.preventDefault();
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
    .bxb_content {
        max-height: 380px;
        overflow-y: scroll;
        position: relative;
    }
    .bxb_content__item {
        color: #444;
        background-color: #fff;
        border: 1px solid #fafafa;
        padding: .05rem 1.25rem;
        /*max-height: 480px;
        overflow-y: scroll;*/
        position: relative;
    }
    .bxb_content__item:focus {
        color: #222;
        background-color: #fff;
        border-color: #3bceff;
        outline: 0;
    }
    
    #right-click-menu{
        background: #FAFAFA;
        border: 1px solid #BDBDBD;
        box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);
        display: block;
        margin: 0;
        padding: 0;
        position: absolute;
        z-index: 999999;
    }

    #right-click-menu li {
        border-bottom: 1px solid #E0E0E0;
        margin: 0;
        padding: 5px 35px;
    }

    #right-click-menu li:last-child {
        border-bottom: none;
    }

    #right-click-menu li:hover {
        background: #1E88E5;
        color: #FAFAFA;
    }
</style>
