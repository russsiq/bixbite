@if ($category->root and ! $category->children)
    <li class="nav-item" itemprop="name"><a href="{{ $category->url }}" class="nav-link" itemprop="url">{{ $category->title }}</a></li>
@elseif ($category->root and $category->children)
    <li class="nav-item dropdown" itemprop="name">
        <a href="{{ $category->url }}" class="nav-link dropdown-toggle" itemprop="url" data-toggle="dropdown">{{ $category->title }}</a>
        <ul class="dropdown-menu">
            @each('components.partials.navigation', $category->children, 'category')
        </ul>
    </li>
@else
    <li class="list-unstyled" itemprop="name">
        <a href="{{ $category->url }}" class="dropdown-item" itemprop="url">{{ $category->title }}</a>
        @isset($category->children)
            <ul style="-webkit-padding-start:10px;">
                @each('components.partials.navigation', $category->children, 'category')
            </ul>
        @endisset
    </li>
@endif
