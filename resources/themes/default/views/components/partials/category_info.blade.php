@php
    $category = pageinfo('category');
    $sub_categories = pageinfo('categories')->where('parent_id', $category->id);
@endphp

{{-- Если у категории есть прикрепленное изображение, либо информация, либо она содержит подкатегории, то отображаем секцию. --}}
{{-- $category->image - лишний запрос, если $category->image_id == null --}}
@if (($category->image_id and $category->image) or $category->info or $sub_categories->count())
    <section class="archive_page__second">
        @if ($category->image_id and $category->image)
            <figure class="">
                <img src="{{ $category->image->url }}" alt="{{ $category->title }}" />
            </figure>
        @endif
        @if ($category->info)
            <p class="archive_page__teaser">{{ $category->info }}</p>
        @endif
        {{ wrap_attr(
            $sub_categories,
            '<li class="cat-links"><a href="%url" rel="category">%title</a></li>',
            '&nbsp;',
            '<ul class="above-entry-meta">%entities</ul>'
            ) }}
    </section>
@endif
