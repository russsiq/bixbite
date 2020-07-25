<div class="header__top">
    <div class="inner-wrap">
        <div class="header_top__inner">
            <div class="header_top__item-left"><a href="mailto:info@site.com"><i class="fa fa-envelope"></i> info@site.com</a></div>
            <div class="header_top__item-right"><a href="tel:88001234567"><i class="fa fa-phone-square"></i> 8 (800) 123-45-67</a></div>
        </div>
    </div>
</div>

<section class="header_area">
    <div class="inner-wrap">
        <div class="header_area__inner">
            <div class="header_area__item-left">
                <a href="{{ route('home') }}" rel="home">
                    <h1 class="site-title" itemprop="name">{{ config('app.name') }}</h1>
                </a>
                <p class="site-description" itemprop="description">От блога до портала</p>
            </div>
            <div class="header_area__item-right">
                <form action="{{ route('articles.search') }}" method="post" class="header_area__form">
                    <div class="header_area__input-group">
                        <input type="search" name="query" class="header_area__input" placeholder="@lang('common.query')" aria-label="Search" autocomplete="off" />
                        <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="header_area__submit">@lang('common.btn.search')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
