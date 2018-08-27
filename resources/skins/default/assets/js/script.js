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

});
