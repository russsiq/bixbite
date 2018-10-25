/**
 * Personal theme or skin js functions.
 */

// Specify a basic functions to execute when the DOM is fully loaded.
$(function() {
    // Ajax send feedback.
    $(document).on('submit', '#feedback_form', function(e) {
        let loader = LoadingLayer.show({active: true});
        
        axios({
            method: 'post',
            url: this.action,
            data: new FormData(this)
        })
        .then(function (response) {
            Notification.success({message: response.data.message});
            this.reset();
        })
        .catch(function (error) {
            console.log(error);
            
            if (error.response.status === 422) {
                for(let k in error.response.data.errors) {
                    Notification.warning({message: error.response.data.errors[k][0]});
                }
            } else {
                Notification.error({message: error.response.data.message});
            }
        })
        .finally(function () {
            loader.hide()
            // grecaptcha_reload()
        });
        
        e.preventDefault();
    });
    
    // grecaptcha_reload();
});
