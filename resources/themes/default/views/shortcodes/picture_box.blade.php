<figure data-id="{{ $id }}" class="single_article__image">
    <picture class="single_article_image__inner">
        {{ wrap_attr(
            $srcsets, '<source media="(max-width: %size)" srcset="%url" type="%mime_type" />', ''
        ) }}
        <img src="{{ $url }}" alt="{{ $alt }}" class="single_article_image__img" />
    </picture>
    <figcaption class="single_article_image__caption">{{ $title }}</figcaption>
</figure>
@if ($description)
    <blockquote>{{ $description }}</blockquote>
@endif

{{-- Variables: $id, $url, $alt, $title, $description, $srcsets (size, url, mime_type) --}}
