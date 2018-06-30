@php
    $category = pageinfo('category');
    $sub_categories = pageinfo('categories')->where('parent_id', $category->id);
    $sub_categories_count = $sub_categories->count();
@endphp

@if (! pageinfo('page') and ($category->img['full'] or $category->info))
    <article class="post type-post status-publish format-standard has-post-thumbnail hentry">
        @if (pageinfo('category')->img['full'])
            <div class="featured-image">
                <img src="{{ theme_asset('images/projects/') }}./.{{ pageinfo('category')->img['full'] }}" alt="{{ pageinfo('category')->title }}">
            </div>
        @endif
        <div class="article-content clearfix">
            {{ wrap_attr(
                pageinfo('categories')->where('parent_id', pageinfo('category')->id),
                '<span class="cat-links"><a href="%url" style="background:#81d742" rel="category">%title</a></span>',
                '&nbsp;',
                '<div class="above-entry-meta">%entities</div>'
                ) }}
            <div class="entry-content clearfix">
                @if (pageinfo('category')->info)
                    <p>{{ pageinfo('category')->info }}</p>
                @endif
            </div>
        </div>
    </article>
@endif

@foreach($sub_categories as $k => $sub_category)
    <article class="post type-post status-publish format-standard has-post-thumbnail hentry">
        @if ($sub_category->img['full'])
            <div class="featured-image">
                <img src="{{ theme_asset('images/projects/') }}/{{ $sub_category->img['full'] }}" alt="{{ $sub_category->title }}">
            </div>
        @endif
        <div class="article-content clearfix">
            <header class="entry-header">
                <h2 class="entry-title">
                    <a href="{{ $sub_category->url }}">{{ $sub_category->title }}</a>
                </h2>
            </header>
            <div class="entry-content clearfix">
                @if ($sub_category->info)
                    <p>{{ $category->info }}</p>
                @endif
            </div>
        </div>
    </article>
@endforeach
