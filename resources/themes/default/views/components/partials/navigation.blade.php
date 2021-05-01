@if ($category->is_root)
    @if ($category->children)
    <li class="nav-item dropdown">
        <a href="{{ $category->url }}" class="nav-link dropdown-toggle" itemprop="url" data-toggle="dropdown">
            <span itemprop="name">{{ $category->title }}</span>
        </a>
        <ul class="dropdown-menu">
            @each('components.partials.navigation', $category->children, 'category')
        </ul>
    </li>
    @else
    <li class="nav-item">
        <a href="{{ $category->url }}" class="nav-link" itemprop="url">
            <span itemprop="name">{{ $category->title }}</span>
        </a>
    </li>
    @endif
@else
    <li class="list-unstyled">
        <a href="{{ $category->url }}" class="dropdown-item" itemprop="url">
            <span itemprop="name">{{ $category->title }}</span>
        </a>
        @isset($category->children)
            <ul style="-webkit-padding-start:10px;">
                @each('components.partials.navigation', $category->children, 'category')
            </ul>
        @endisset
    </li>
@endif
