<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach ($articles as $article)
    <url>
        <loc>{{ $article->url }}</loc>
        @if ($article->updated_at)
            <lastmod>{{ $article->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        @endif
        <changefreq>{{ setting('system.articles_changefreq', 'weekly') }}</changefreq>
        <priority>{{ setting('system.articles_priority', '0.4') }}</priority>
        @if ($article->image)
            <image:image>
                <image:loc>{{ $article->image->url }}</image:loc>
            </image:image>
        @endif
    </url>
@endforeach
</urlset>
