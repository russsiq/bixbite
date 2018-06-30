<div class="header-top">
    <div class="inner-wrap">
        <div class="header-top__inner">
            <div class="header-top__item-left"><a href="mailto:info@site.com"><i class="fa fa-envelope"></i> info@site.com</a></div>
            <div class="header-top__item-right"><a href="tel:88001234567"><i class="fa fa-phone-square"></i> 8 (800) 123-45-67</a></div>
        </div>
    </div>
</div>

<section class="header-area">
    <div class="inner-wrap">
        <div class="header-area__inner">
            <div class="header-area__item-left">
                <a href="{{ route('home') }}" rel="home">
                    <h1 class="site-title">{{ setting('system.app_name') }}</h1>
                </a>
                <p class="site-description">От блога до портала</p>
            </div>
            <div class="header-area__item-right">
                <form action="{{ route('articles.search') }}" method="post" class="header-area__form">
                    <div class="header-area__input-group">
                        <input type="search" name="query" class="header-area__input" placeholder="@lang('common.placeholder.query')" aria-label="Search" autocomplete="off" />
                        <div class="header-area__input-group-append">
                            <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="header-area__submit">@lang('common.btn.search')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
