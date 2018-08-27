/**
 * Request JSON - new style ajax
 */
$.reqJSON = function(url, params, callback) {
    var loader = LoadingLayer.show({active: true});
    
    $.ajax({
        url: url,
        data: params,
        cache: false,
        type: 'POST',
        dataType: 'json',
        beforeSend: function(jqXHR) {
            jqXHR.overrideMimeType("application/json; charset=UTF-8");
            // Repeat send header ajax
            jqXHR.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        },
    })
    .done(function(data, textStatus, jqXHR) {
        if (typeof(data) == 'object') {
            if (data.status) {
                // this schema {"status": true, "message": "...", ...etc}
                callback.call(null, data);
            } else {
                // this schema {"status": false, "message": "...", ...etc}
                Notification.error({message: data.message});
            }
        } else {
            data = $.parseJSON(data);
            if (typeof(data) == 'object') {
                callback.call(null, data);
            } else {
                Notification.error({message: '<i><b>Bad reply from server</b></i>'});
            }
        }
    })
    .always(function(jqXHR, textStatus, errorThrown) {
        loader.hide();
    })
    .catch(function(jqXHR, textStatus, exception) {
        var msg, msgs;
        
        if(jqXHR.status === 0) msg = 'Not connect. Verify Network.';
        else if(jqXHR.status == 404) msg = 'Requested page not found.';
        else if(jqXHR.status == 422 && jqXHR.responseJSON.errors) msgs = jqXHR.responseJSON.errors;
        else if(jqXHR.status == 500) msg = 'Internal Server Error.';
        else if('timeout' === exception) msg = 'Time out error.';
        else if('abort' === exception) msg = 'Ajax request aborted.';
        else if('parsererror' === exception) msg = 'Requested JSON parse failed.';
        else msg = 'Uncaught Error #'+jqXHR.status+': '+jqXHR.statusText;
        
        if (!msgs) {
            Notification.error({message: msg});
        } else {
            $.each(msgs, function(index, error) {
                Notification.warning({message: error});
            });
        }
    });
}

$.fn.row = function(i) {
    return $('tr:nth-child('+(i+1)+') td', this);
}
$.fn.column = function(i) {
    return $('tr td:nth-child('+(i+1)+')', this);
}

// Specify a basic functions to execute when the DOM is fully loaded.
$(function() {
    // For checked checkbox in table cell
    $('input[type=checkbox]').each(function() {
        if ( $(this).prop('checked') == true ) {
            $(this).parents('label').addClass('active');
            $(this).parents('td').addClass('active');
        } else {
            $(this).parents('label').removeClass('active');
            $(this).parents('td').removeClass('active');
        }
    });

    // Select/unselect all
    $(document).on('click', 'table .select-all', function() {
        if ($(this).parents('th').length > 0) {
            ownerIndex = $(this).parents('th').index() + 1;
            $(this).parents('table').find('tr td:nth-child('+ownerIndex+') input:checkbox:not([disabled])').prop('checked', $(this).prop('checked'));
        } else {
            $(this).closest('tr').find('td input:checkbox:not([disabled])').prop('checked', $(this).prop('checked'));
        }
    });

    // Process spoilers
    $(document).on('click', '.sp-head', function() {
        if ($(this).hasClass("expanded")) {
            $(this).removeClass("expanded");
            $(this).next('.sp-body').slideUp("fast");
        } else {
            $(this).addClass("expanded");
            $(this).next('.sp-body').slideDown("fast");
        }

    });
});

/* cookie style core */
function setCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function deleteCookie(name) {
    setCookie(name,"",-1);
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// confirmIt
function confirmIt(url, text){
    if (confirm(text)) document.location=url;
    return false;
}

// insertext
function insertext(open, close, field) {
    msgfield = document.getElementById((field != '') ? field : 'content');

    // IE support
    if (document.selection && document.selection.createRange){
        msgfield.focus();
        sel = document.selection.createRange();
        sel.text = open + sel.text + close;
        msgfield.focus();
    }
    // Moz support
    else if (msgfield.selectionStart || msgfield.selectionStart == "0"){
        var startPos = msgfield.selectionStart;
        var endPos = msgfield.selectionEnd;

        msgfield.value = msgfield.value.substring(0, startPos) + open + msgfield.value.substring(startPos, endPos) + close + msgfield.value.substring(endPos, msgfield.value.length);
        msgfield.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
        msgfield.focus();
    }
    // Fallback support for other browsers
    else {
        msgfield.value += open + close;
        msgfield.focus();
    }
    $('html, body').animate({ scrollTop: $(msgfield).offset().top-200 }, 888);
    return;
}

// insertimage
function insertimage(open) {
    insertext(open, ' ');
}

/* Quote user */
var q_txt = '';

function copy_quote(q_name) {

    if (window.getSelection) {
        q_txt = window.getSelection();
    } else if (document.getSelection) {
        q_txt = document.getSelection();
    } else if (document.selection) {
        q_txt = document.selection.createRange().text;
    }

    if (q_txt == '') {
        q_txt = '[b]'+q_name+'[/b],';
    } else {
        q_txt = '[quote='+q_name+']'+q_txt+'[/quote]';
    }

}

function quote(q_name) {
    insertext(q_txt, ' ', 'content');
}

// formatSize
function formatSize($file_size){
    if ($file_size >= 1073741824) {
        $file_size = Math.round( $file_size / 1073741824 * 100 ) / 100 + " Gb";
    } else if ($file_size >= 1048576) {
        $file_size = Math.round( $file_size / 1048576 * 100 ) / 100 + " Mb";
    } else if ($file_size >= 1024) {
        $file_size = Math.round( $file_size / 1024 * 100 ) / 100 + " Kb";
    } else {
        $file_size = $file_size + " b";
    }
    return $file_size;
}

// calculateMaxLen
function calculateMaxLen(oId, tId, maxLen) {
    var delta = maxLen - oId.val().length;

    if (tId) {
        tId.html(delta);
        tId.css('color', ((delta > 0) ? 'black' : 'red'));
    }
}

// Simple timer
function timerShow(id) {
    var timer = 0,hour = 0,minute = 0,second = 0;
    window.setInterval(function(){
        ++timer;
        hour   = Math.floor(timer / 3600);
        minute = Math.floor((timer - hour * 3600) / 60);
        second = timer - hour * 3600 - minute * 60;
        if (hour < 10) hour = '0' + hour;
        if (minute < 10) minute = '0' + minute;
        if (second < 10) second = '0' + second;
        $('#'+id).html(hour + ':' + minute + ':' + second);
        }, 1000);
}

// printElem
function printElem(data) {
    
    var printing_css='<style>* {color:#888;} input{display:none;} a {text-decoration:none;}</style>';
    var html_to_print=printing_css + data;
    var iframe=$('<iframe id="print_frame">');
    $('body').append(iframe);
    var doc = $('#print_frame')[0].contentDocument || $('#print_frame')[0].contentWindow.document;
    var win = $('#print_frame')[0].contentWindow || $('#print_frame')[0];
    doc.getElementsByTagName('body')[0].innerHTML=html_to_print;
    win.print();
    $('iframe').remove();

    return true;
}

/* Main function to show Modal Bootsrtap */
function showModal(textOrID, header, footer, size) {
    var withID = document.getElementById(textOrID);
    if (withID && !header && !footer) { // Show modal with ID
        $(withID).modal('show');
        return;
    }
    var modalContent = '';
    if (header) {
        if (textOrID) {
            modalContent = '<div class="modal-header">\
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                    <span aria-hidden="true">&times;</span>\
                                </button>\
                                <h5 class="modal-title">' + header + '</h5>\
                            </div>';
        } else {
            modalContent = '<div class="modal-header">\
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                    <span aria-hidden="true">&times;</span>\
                                </button>\
                                <h5 class="modal-title">Info</h5>\
                            </div>';
        }
    } else {
        modalContent = '<div class="modal-header">\
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                <span aria-hidden="true">&times;</span>\
                            </button>\
                            <h5 class="modal-title">Error</h5>\
                        </div>';
    }
    if (textOrID)
        modalContent += '<div class="modal-body">' + textOrID + '</div>';
    else
        modalContent += '<div class="modal-body">Unable to load content . . .</div>';
    
    if (footer) {
        modalContent += '<div class="modal-footer">' + footer + '</div>';
    } else {
        modalContent += '<div class="modal-footer">\
                            <button type="button" class="btn btn-default btn-secondary" data-dismiss="modal">\
                            Close\
                            </button>\
                        </div>';
    }
    if (size == 'modal-lg')
        $('#modal_dialog .modal-dialog').addClass('modal-lg');
    else
        $('#modal_dialog .modal-dialog').removeClass('modal-lg');

    $('#modal_dialog .modal-content').html(modalContent); // #modal_dialog isset in html document'е
    $('#modal_dialog').modal('show');

    return;
}

// validateFile
function validateFile(fileInput,multiple,fileMaxSize) {
    var htext = '';
    var hsize = '';
    var btnFileInput = $(fileInput).closest('.btn-fileinput');
    
    if (!fileInput.value) {
        btnFileInput.attr('style', '');
        btnFileInput.addClass('btn');
        btnFileInput.children('span').eq(0).html('<i class="fa fa-plus"></i> Add files ...');
        btnFileInput.children('span').attr('style', '');
        return false;
    }
    
    if (multiple) {
        for (var i=0;i<fileInput.files.length;i++) {
            if (fileMaxSize) {
                htext += '<tr><td style="overflow:hidden;text-overflow:ellipsis;max-width: 400px;">' + fileInput.files[i].name+'</td><td nowrap><b class="pull-right' + (fileInput.files[i].size>fileMaxSize?' text-danger':'') + '">'+formatSize(fileInput.files[i].size)+'</b></td></tr>';
            } else {
                htext += '<tr><td style="overflow:hidden;text-overflow:ellipsis;max-width: 400px;">' + fileInput.files[i].name+'</td><td nowrap><b class="pull-right">'+formatSize(fileInput.files[i].size)+'</td></tr>';
            }
            hsize = Number(fileInput.files[i].size) + Number(hsize);
        }
        
        btnFileInput.removeClass('btn');
        btnFileInput.children('span').eq(0).html('<table\
            class="table-condensed" style="width: 100%;">\
            ' + htext + '<tr><td colspan="2" class="text-right">' + formatSize(hsize) + '</td></tr></table><div class="progress"><div id="progressbar" class="progress-bar progress-bar-success" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div>');
        btnFileInput.children('span').eq(0).css({'width': '100%', 'display': 'block'/*, 'white-space': 'nowrap'*/});
        btnFileInput.css({'width': '100%', 'display': 'block'});
    } else {
        for (var i=0;i< fileInput.files.length;i++) {
            htext += fileInput.files[i].name+' ('+formatSize(fileInput.files[i].size)+')<br />';
            hsize = Number(fileInput.files[i].size) + Number(hsize);
        }
        
        btnFileInput.children('span').eq(0).html(htext);
    }
    
    return true;
}
