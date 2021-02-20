@props(['id', 'items', 'count', 'title' => 'title', 'description' => 'description', 'image' => 'image', 'url' => 'url'])

<section id="{{ $id = 'carousel-'.md5($items) }}" data-bs-ride="carousel" class="carousel slide">
    <div class="carousel-indicators">
        @foreach (range(1, $count) as $item)
        <button type="button" data-bs-target="#{{ $id }}" data-bs-slide-to="{{ $loop->index }}"
            aria-label="@lang('Slide :number', ['number' => $loop->iteration])"
            aria-current="{{ $loop->first ? 'true' : '' }}" class="{{ $loop->first ? 'active' : '' }}"></button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach ($items as $item)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
            @if ($image = $item->{$image})
            <img src="{{ $image }}" alt="{{ $item->{$title} }}" class="d-block w-100" />
            @else
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" class="d-block w-100">
                <rect width="100%" height="100%" fill="#888" />
            </svg>
            @endif

            <div class="container">
                <div class="carousel-caption d-none d-md-block">
                    <h3>{{ $item->{$title} }}</h3>
                    <p>{{ $item->{$description} }}</p>
                    @if ($url = $item->{$url})
                    <hr>
                    <p><a href="{{ $url }}" class="btn btn-primary">Go somewhere</a></p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <button type="button" data-bs-target="#{{ $id }}" data-bs-slide="prev" class="carousel-control-prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">@lang('Previous')</span>
    </button>

    <button type="button" data-bs-target="#{{ $id }}" data-bs-slide="next" class="carousel-control-next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">@lang('Next')</span>
    </button>
</section>
