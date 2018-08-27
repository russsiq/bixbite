/**
 * Request JSON - new style ajax.
 */
$.reqJSON = function(url, params, callback) {
    let loader = LoadingLayer.show({active: true});
    
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
        let msg, msgs;
        
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
            for(let k in msgs) {
                Notification.warning({message: msgs[k][0]});
            }
        }
    });
}


/**
 * Comment actions.
 */

// Reload captcha.
function reload_captcha() {
    $('#img_captcha').attr('src', $('#img_captcha').attr('src').replace(/(rand=)[0\.?\d*]+/, '$1' + Math.random()));
    $("input[name=captcha]").val('');
}

// Specify a basic functions to execute when the DOM is fully loaded.
$(function() {
    $(document).on('click', '#img_captcha', function() {
        reload_captcha();
    });

    $(document).on('keypress', '#respond__form textarea[name=content]', function (e) {
        if(e.keyCode==10 || (e.ctrlKey && e.keyCode==13)) {
            $('#respond__form').submit();
        }
    });

    $(document).on('click', '.comment__reply, #comment__reply-cancel', function(e) {
        e.preventDefault();
        $("#comment__reply-cancel").hide();
        let clone = $('#respond').clone(true);
        $('#respond').slideUp('slow', function() {$(this).remove();});
        let mid = $(this).attr('data-respond');
        if (mid > 0) {
            $(clone).insertAfter('#comment-' + mid).hide().slideDown('slow', function () {
                $("textarea[name=content]").focus();
            });
            $("input[name=parent_id]").val(mid);
            $("#comment__reply-cancel").show();
            $('html, body').animate({scrollTop: $(clone).offset().top - 87}, 888);
        } else {
            $(clone).insertAfter('.comments__list:last').hide().slideDown('slow');
            $("input[name=parent_id]").val('');
        }
    });

    // Ajax send comment.
    $(document).on('submit', '#respond__form', function(e) {
        e.preventDefault();
        
        $.reqJSON(
            $('#respond__form').attr('action'),
            $('#respond__form').serializeArray(),
            function(json) {
                Notification.success({message: json.message});
                $("textarea[name=content]").val('');
                let comment = $("input[name=parent_id]").val() > ''
                    ? $('<ul class="children">' + json.comment + '</ul>')
                    : $('<ol class="comments__list">' + json.comment + '</ol>');
                comment.insertBefore($('#respond')).hide().slideDown('slow');
                $('html, body').animate({scrollTop: comment.offset().top - 87}, 888);
                if (($("input[name=captcha]").length > 0)){reload_captcha();}
                if (($("input[name=parent_id]").val() > '')) $("#comment__reply-cancel").click();
            }
        );
    });
});

/**
 * Personal theme functions.
 */

// Specify a basic functions to execute when the DOM is fully loaded.
$(function() {
    // Ajax send feedback.
    $(document).on('submit', '#feedback_form', function(e) {
        e.preventDefault();
        
        let loader = LoadingLayer.show({active: true});
        
        axios({
            method: 'post',
            url: this.action,
            data: new FormData(this)
        })
        .then(function (response) {
            loader.hide();
            // grecaptcha_reload();
            
            Notification.success({message: response.data.message});
            this.reset();
        })
        .catch(function (error) {
            loader.hide();
            // grecaptcha_reload();
            
            console.log(error);
            
            if (error.response.status === 422) {
                for(let k in error.response.data.errors) {
                    Notification.warning({message: error.response.data.errors[k][0]});
                }
            } else {
                Notification.error({message: error.response.data.message});
            }
        });
    });
    
    // grecaptcha_reload();
});
