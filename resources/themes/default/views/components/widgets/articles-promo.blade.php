{{-- Отобразим ссылку на очистку кеша только собственнику сайта. --}}
@role('owner')
    <a href="{{ $clearCacheUrl }}" class="moder_panel"><i class="fa fa-recycle"></i></a>
@endrole

<div class="col-lg-6">
    @if($article = $articles->first())
        <div style="position: relative; margin-bottom: 1.25rem;">
            <a href="{{ $article->url }}" class="" style="
                    position: relative;
                    float: left;
                    width: 100%;
                    margin-right: -100%;
                    -webkit-backface-visibility: hidden;
                    backface-visibility: hidden;
                    transition: transform .6s ease-in-out;
                    height: 375px;
                    background-size:cover;
                    background-position: center center;
                    @if ($image = $article->image)
                        background-image: url({{ $image->getUrlAttribute('medium') ?? $image->getUrlAttribute('small') ?? $image->getUrlAttribute('thumb') }})
                    @endif
                ">
                <div class="d-none d-md-block"
                    style="
                        position: absolute;
                        right: 15%;
                        bottom: 20px;
                        left: 15%;
                        z-index: 10;
                        padding-top: 20px;
                        padding-bottom: 20px;
                        color: #fff;
                        text-align: center;
                        background: linear-gradient(180deg,transparent,rgba(0,0,0,.8));
                        width: 100%;
                        right: 0;
                        left: 0;
                        bottom: 0;
                    ">
                    <h5 style="color: #ffffff; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">{{ $article->title }}</h5>
                    <p><small><i class="fa fa-calendar-o"></i>&nbsp;{{ $article->created }}</small></p>
                </div>
            </a>
        </div>
    @endisset
</div>

<div class="col-lg-6">
    <div class="widget__beside_slider">
        <div class="widget__beside_slider__inner">
            @foreach ($articles->skip(1) as $article)
                <a href="{{ $article->url }}" title="{{ $article->title }}" class="widget__beside_slider__item">
                    <div class="widget__beside_slider__image" style="
                        @if ($image = $article->image)
                            background-image: url({{ $image->getUrlAttribute('small') ?? $image->getUrlAttribute('thumb') }})
                        @endif
                    "></div>
                    <div class="widget__beside_slider__caption">
                        <h5 class="widget__beside_slider__title">{{ $article->title }}</h5>
                        <p class="widget__beside_slider__descr"><i class="fa fa-calendar-o"></i>&nbsp; {{ $article->created }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
