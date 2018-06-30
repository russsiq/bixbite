$(function() {

    /** X-CSRF-TOKEN */
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    /** Reload captcha */
    $(document).on('click', '#img_captcha', function() {
        reloadCaptcha();
    });

    $(document).on('keypress', '#comment_form textarea[name=content]', function (e) {
        if(e.keyCode==10 || (e.ctrlKey && e.keyCode==13)) {
            $('#comment_form').submit();
        }
    });

    $(document).on('click', '.comment-reply-link, #cancel-comment-reply-link', function(e) {
        e.preventDefault();
        $("#cancel-comment-reply-link").hide();
        var clone = $('#respond').clone(true);
        $('#respond').slideUp("slow", function() {$(this).remove();});
        var mid = $(this).attr('data-respond');
        if (mid > 0) {
            $(clone).insertAfter('#comment-' + mid).hide().slideDown('slow', function () {
                $("textarea[name=content]").focus();
            });
            $("input[name=parent_id]").val(mid);
            $("#cancel-comment-reply-link").show();
            $('html, body').animate({scrollTop: $(clone).offset().top - 87}, 888);
        } else {
            $(clone).insertAfter('.comments-list:last').hide().slideDown('slow');
            $("input[name=parent_id]").val('');
        }
    });
    
    /** Ajax send_comment */
    $(document).on('submit', '#comment_form', function(e) {
        showLoadingLayer();
        axios({
            method: 'post',
            url: this.action,
            data: new FormData(this)
        })
        .then(function (response) {
            $.notify({message: response.data.message}, {type: 'success'});
            $("textarea[name=content]").val('');
            var comment = $("input[name=parent_id]").val() > ''
                ? $('<ul class="children">' + response.data.comment + '</ul>')
                : $('<ol class="comments-list">' + response.data.comment + '</ol>');
            comment.insertBefore($('#respond')).hide().slideDown('slow');
            $('html, body').animate({scrollTop: comment.offset().top - 87}, 888);
            if (($("input[name=captcha]").length > 0)){reloadCaptcha();}
            if (($("input[name=parent_id]").val() > '')) $("#cancel-comment-reply-link").click();
        })
        .catch(function (error) {
            console.log(error);
            if (error.response.status === 422) {
                for(var k in error.response.data.errors) {
                    $.notify({message: error.response.data.errors[k][0]}, {type: 'warning'});
                }
            } else {
                $.notify({message: error.response.data.message}, {type: 'danger'});
            }
        });
        hideLoadingLayer();
        e.preventDefault();
    });
    
    /** Ajax send feedback */
    $(document).on('submit', '#feedback_form', function(e) {
        showLoadingLayer();
        axios({
            method: 'post',
            url: this.action,
            data: new FormData(this)
        })
        .then(function (response) {
            $.notify({message: response.data.message}, {type: 'success'});
            this.reset();
        })
        .catch(function (error) {
            console.log(error);
            if (error.response.status === 422) {
                for(var k in error.response.data.errors) {
                    $.notify({message: error.response.data.errors[k][0]}, {type: 'warning'});
                }
            } else {
                $.notify({message: error.response.data.message}, {type: 'danger'});
            }
        });
        hideLoadingLayer();
        e.preventDefault();
    });
    
});

// showLoadingLayer
window.showLoadingLayer = function () {
    var setX = ( $(window).width() - $("#loading-layer").width() ) / 2;
    var setY = ( $(window).height() - $("#loading-layer").height() ) / 2;

    $("#loading-layer").css({
        left : setX + "px",
        top : setY + "px",
        position : 'fixed',
        zIndex : '99'
    });

    $("#loading-layer").fadeIn('slow');
}

// hideLoadingLayer
window.hideLoadingLayer = function () {
    $("#loading-layer").fadeOut('slow');
}

// Reload captcha
window.reloadCaptcha = function() {
    $('#img_captcha').attr('src', $('#img_captcha').attr('src').replace(/(rand=)[0\.?\d*]+/, '$1' + Math.random()));
    $("input[name=captcha]").val('');
}
