<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap-home.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap-categories.xml') }}</loc>
        @if ($category->updated_at)
            <lastmod>{{ $category->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        @endif
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap-articles.xml') }}</loc>
        @if ($article->updated_at)
            <lastmod>{{ $article->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        @endif
    </sitemap>
</sitemapindex>
