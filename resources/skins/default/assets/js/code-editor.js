'use strict';

/**
 * Load all of code editor JavaScript dependencies.
 */

try {
    window.CodeMirror = require('codemirror');
    require('codemirror/mode/htmlmixed/htmlmixed.js');
    require('codemirror/mode/css/css.js');
    require('codemirror/mode/javascript/javascript.js');
    require('codemirror/mode/php/php.js');
    require('codemirror/addon/display/fullscreen.js');
    require('codemirror/addon/dialog/dialog.js');
    require('codemirror/addon/search/search.js');
    require('codemirror/addon/search/searchcursor.js');
    window.emmetCodeMirror = require('emmet-codemirror');
} catch (e) {}

var template = false;

function codeEditor_refresh() {
    var loader = LoadingLayer.show({active: true});

    axios.get(window.location.href+'/:template/edit', {
        params: {
            template: template
        }
    })
    .then(function (response) {
        $('[name="template"]').val(template);
        $('.template__title').html(response.data.file.size+'; '+response.data.file.modified);
        $('.CodeMirror').remove();
        $('#codeEditor').val(response.data.file.content);

        let tmode;
        if (template.indexOf(".ini") > 0) tmode = "text/x-ini";
        if (template.indexOf(".tpl") > 0) tmode = "text/html";
        if (template.indexOf(".html") > 0) tmode = "text/html";
        if (template.indexOf(".css") > 0) tmode = "text/x-gss";
        if (template.indexOf(".js") > 0) tmode = "javascript";
        if (template.indexOf(".php") > 0) tmode = "php";

        let editor = CodeMirror.fromTextArea(
            document.getElementById('codeEditor'),
            {
                lineNumbers: true,
                mode: tmode,
                // profile: 'xhtml',
                lineWrapping: true,
                styleActiveLine: true,
                indentUnit: 4,
                tabMode: 'shift',
                enterMode: 'indent',
                theme: 'default',
                extraKeys: {
                    "F11": function(editor) {editor.setOption("fullScreen", ! editor.getOption("fullScreen"));},
                    "Esc": function(editor) {if (editor.getOption("fullScreen")) editor.setOption("fullScreen", false);},
                    "Tab": "indentMore",
                    "Shift-Tab": "indentLess",
                }
        });
        editor.on("change", function(editor) {
            $("#codeEditor").val(editor.getValue());
        });
        $('.CodeMirror').css({
            // 'font-size': '1rem',
            'height': '100%'
        });
        emmetCodeMirror(editor);
        loader.hide();
    })
    .catch(function (error) {
        console.log(error);
        if (error.response.status === 422) {
            for(var k in error.response.data.errors) {
                Notification.warning({message: error.response.data.errors[k][0]});
            }
        } else {
            Notification.error({message: error.response.data.message});
        }
        loader.hide();
    });
}

function codeEditor_update() {
    var loader = LoadingLayer.show();

    axios.put(window.location.href+'/:template', {
        template: template,
        content: $('#codeEditor').val()
    })
    .then(function (response) {
        if (response.data.status === false) {
            Notification.info({message: response.data.message});
        } else {
            $('.template__title').html(response.data.file.size+'; '+response.data.file.modified);
            Notification.success({message: response.data.message});
        }
        loader.hide();
    })
    .catch(function (error) {
        console.log(error);
        if (error.response.status === 422) {
            for(var k in error.response.data.errors) {
                Notification.warning({message: error.response.data.errors[k][0]});
            }
        } else {
            Notification.error({message: error.response.data.message});
        }
        loader.hide();
    });
}

$(document).on('click', '[data-path]', function(e) {
    template = this.getAttribute('data-path');
    e.preventDefault();
    codeEditor_refresh();
});

$(document).on('click', '#template__save', function(e) {
    if(template.length > 0) {
        codeEditor_update();
    //} else {
    //    Notification.warning({message: '@lang('msg.not_selected')'});
    }
    e.preventDefault();
});

document.onkeydown = function(e) {
    e = e || event;
    if (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) {
        if(template.length > 0) {
            codeEditor_update();
        //} else {
        //    Notification.warning({message: '@lang('msg.not_selected')'});
        }
        e.preventDefault();
    }
}
