<section class="footer_area">
    <div class="inner-wrap">
        <div class="footer_area__inner">
            <div class="footer_area__about">
                <h4 class="footer_area__title">{{ setting('system.app_name', 'BixBite') }}</h4>
                <p class="footer_area__text">We love BixBite and we are here to provide you with professional looking WordPress themes so that you can take your website one step ahead. We focus on simplicity, elegant design and clean code.
                </p>
            </div>
            <div class="footer_area__contact">
                <h4 class="footer_area__title">Useful Links</h4>
                <ul class="footer_area__list">
                    <li class="footer_area__item"><a href="#" class="page_footer__link">Documentation</a></li>
                    <li class="footer_area__item"><a href="#" class="page_footer__link">FAQ</a></li>
                    <li class="footer_area__item"><a href="#" class="page_footer__link">Themes</a></li>
                    <li class="footer_area__item"><a href="#" class="page_footer__link">Modules</a></li>
                    <li class="footer_area__item"><a href="{{ route('feedback.create') }}" class="page_footer__link">Поддержка</a></li>
                </ul>
            </div>
            <div class="footer_area__feedback">
                <h4 class="footer_area__title">Связаться с нами</h4>

                <a href="{{ route('feedback.create') }}" class="btn btn-outline-primary">Форма обратной связи</a>
            </div>
        </div>
    </div>
</section>
