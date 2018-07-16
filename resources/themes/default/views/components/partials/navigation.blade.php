@if ($item->root and ! $item->children)
    <li class="nav-item" itemprop="name"><a href="{{ $item->url }}" class="nav-link" itemprop="url">{{ $item->title }}</a></li>
@elseif ($item->root and $item->children)
    <li class="nav-item dropdown" itemprop="name">
        <a href="{{ $item->url }}" class="nav-link dropdown-toggle" itemprop="url" data-toggle="dropdown">{{ $item->title }}</a>
        <ul class="dropdown-menu">
            @each('components.partials.categories', $item->children, 'item')
        </ul>
    </li>
@else
    <li class="list-unstyled" itemprop="name">
        <a href="{{ $item->url }}" class="dropdown-item" itemprop="url">{{ $item->title }}</a>
        @isset($item->children)
            <ul style="-webkit-padding-start:10px;">
                @each('components.partials.categories', $item->children, 'item')
            </ul>
        @endisset
    </li>
@endif
