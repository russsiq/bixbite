@if ($item->root and ! $item->children)
    <li class="nav-item"><a href="{{ $item->url }}" class="nav-link">{{ $item->title }}</a></li>
@elseif ($item->root and $item->children)
    <li class="nav-item dropdown">
        <a href="{{ $item->url }}" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ $item->title }}</a>
        <ul class="dropdown-menu">
            @each('components.partials.categories', $item->children, 'item')
        </ul>
    </li>
@else
    <li class="list-unstyled">
        <a href="{{ $item->url }}" class="dropdown-item">{{ $item->title }} [{{ $item->articles_count }}]</a>
        @isset($item->children)
            <ul style="-webkit-padding-start:10px;">
                @each('components.partials.categories', $item->children, 'item')
            </ul>
        @endisset
    </li>
@endif
