<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="margin-bottom: 1.25rem;">
    <div class="carousel-inner">
        @foreach ($widget->items as $key => $item)
            <a href="{{ $item->url }}" class="carousel-item {{ $loop->first ? 'active' : '' }}" style="
                    height: 375px;
                    background-size:cover;
                    background-position: center center;
                    @if ($item->image)
                        background-image: url({{ $item->image->getUrlAttribute('medium') ?? $item->image->getUrlAttribute('small') }})
                    @endif
                ">
                {{-- @if ($item->image)
                    <img
                        src="{{ $item->image->getUrlAttribute('medium') }}"
                        alt="{{ $item->image->title }}"
                        class="d-block w-100" />
                @endif --}}
                <div class="carousel-caption d-none d-md-block"
                    style="
                        background: linear-gradient(180deg,transparent,rgba(0,0,0,.8));
                        width: 100%;
                        right: 0;
                        left: 0;
                        bottom: 0;
                    ">
                    <h5 style="color: #ffffff; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">{{ teaser($item->title, 70) }}</h5>
                    <p><small><i class="fa fa-calendar-o"></i>&nbsp;{{ $item->created }}</small></p>
                </div>
            </a>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span></a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a>
</div>
