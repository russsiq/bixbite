var $ = jQuery.noConflict();
var attachAbsoluteRowID = 0;

$(function() {

    /**
     * X-CSRF-TOKEN
     *
    */
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    /* admGroup hide/show */
    $('.adm-group-toggle').click(function() {
        if ($(this).hasClass('expanded')) {
            $(this).removeClass('expanded');
            $(this).parents().next('.adm-group-content').slideUp('slow');
        } else {
            $(this).addClass('expanded');
            $(this).parents().next('.adm-group-content').slideDown('slow');
        }
        return false;
    });

    $('code').click(function() {
        select(this);
    });

    // Multiple select input without Ctrl Key
    $('select[multiple] option').mousedown(function(e){
        e.preventDefault();
        var select = this.closest('select');
        var scroll = select.scrollTop;
        e.target.selected = !e.target.selected;
        setTimeout(function(){select.scrollTop = scroll;}, 0);
        $(select).focus();
    }).mousemove(function(e){e.preventDefault()});
    
    // HotKeys to this page
    $(document).on('keydown', function(e) {
        e = e || event;
        if (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) {
            $('form#form_action :submit').click();

            return false;
        }
    });
    
    // Bootstrap tab-pane
    $(document).on('click', ':submit', function() {
        return focus_is_invalid();
    });
    function focus_is_invalid() {
        $('input, textarea, select').filter('[required]:hidden').each(function() {
            if ($(this).is(':invalid')) {
                var tab_id = $(this).closest('.tab-pane').attr('id');
                $('.nav-tabs a[href="#' + tab_id + '"]').tab('show');

                return false;
            }
        });
            
        return true;
    }

    /*****************************
     * ACTION
    ******************************/

    // Добавление элементов (пользователь, группы) в modal
    $(document).on('click', '.add_form', function(){
        $('#modal-dialog .modal-dialog').load($(this).attr('href') + ' #add_edit_form .modal-content');
        $('#modal-dialog').modal('show');
        return false;
    });
    // Редактирование элементов (пользователь, группы) в modal
    $(document).on('click', '.edit_form', function(){
        $('#modal-dialog .modal-dialog').load($(this).attr('href') + ' #add_edit_form .modal-content');
        $('#modal-dialog').modal('show');
        return false;
    });

});

function select(elem) {
    var rng, sel;
    if (document.createRange) {
        rng = document.createRange();
        rng.selectNode(elem);
        sel = window.getSelection();
        var strSel = '' + sel;
        if (!strSel.length) {
            sel.removeAllRanges();
            sel.addRange(rng);
        }
    } else {
        var rng = document.body.createTextRange();
        rng.moveToElementText(elem);
        rng.select();
    }
}

/*
Для input type="file"
HTML
<div class="btn btn-default btn-fileinput">
    <span><i class="fa fa-plus"></i> Add files ...</span>
    <input type="file" name="image" id="image-con" onchange="validateFile(this);">
</div>
*/
