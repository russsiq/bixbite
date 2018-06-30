$(function() {
    
    // Open/close the collapse panels based on history
    $('.collapse').on('hidden.bs.collapse', function () {
        localStorage.removeItem('open_' + this.id);
    });

    $('.collapse').on('shown.bs.collapse', function () {
        localStorage.setItem('open_' + this.id, true);
    });

    $('.collapse').each(function () {
        // Default close unless saved as open
        if (localStorage.getItem('open_' + this.id)) {
            $(this).collapse('show');
        }
    });

    /** X-CSRF-TOKEN */
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

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
