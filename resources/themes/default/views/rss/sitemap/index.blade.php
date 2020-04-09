<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap-home.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap-categories.xml') }}</loc>
        @if ($categoriesLastmod)
            <lastmod>{{ $categoriesLastmod->tz('UTC')->toAtomString() }}</lastmod>
        @endif
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap-articles.xml') }}</loc>
        @if ($articlesLastmod)
            <lastmod>{{ $articlesLastmod->tz('UTC')->toAtomString() }}</lastmod>
        @endif
    </sitemap>
</sitemapindex>
