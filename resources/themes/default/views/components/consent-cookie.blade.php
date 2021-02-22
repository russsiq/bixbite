<div id="consent-cookie" class="consent-cookie">
    <style>
        .consent-cookie {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1030;
            margin: 0 auto;
            padding: 1.5rem .8rem;
            background: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, .2);
        }
    </style>

    <div class="consent-cookie-content">
        <p>Данный сайт использует файлы cookies и сторонние сервисы сбора технических данных посетителей для обеспечения работоспособности и улучшения качества обслуживания.</p>
        <p>Продолжая использовать наш сайт, вы автоматически соглашаетесь с использованием данных технологий.</p>
        <button id="consent-cookie-accept" class="btn btn-primary">Согласен</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function (loadEvent) {
            const CONSENT_BLOCK = document.getElementById('consent-cookie');
            const CONSENT_BUTTON = document.getElementById('consent-cookie-accept');

            CONSENT_BUTTON && CONSENT_BUTTON.addEventListener('click', function (clickEvent) {
                document.cookie = "{{ $cookieKey }}={{ $cookieAccept }} ;path=/ ;expires=0";

                CONSENT_BLOCK
                    && CONSENT_BLOCK.parentNode
                    && CONSENT_BLOCK.parentNode.removeChild(CONSENT_BLOCK);
            });
        });
    </script>
</div>
