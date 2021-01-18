<script src="{{ $api_render.$site_key }}"></script>
<script>
    function grecaptcha_reload() {
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ $site_key }}', {
                    action: 'laravel_grecaptcha'
                })
                .then(function(token) {
                    document.getElementsByName('g-recaptcha-response')
                        .forEach(function(node) {
                            node.value = token;
                        });
                });
        });
    }
    grecaptcha_reload();
</script>
