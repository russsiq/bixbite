<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap-home.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap-categories.xml') }}</loc>
        <lastmod>{{ ($categories->updated_at ?? $categories->created_at)->tz('UTC')->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap-articles.xml') }}</loc>
        <lastmod>{{ ($articles->updated_at ?? $articles->created_at)->tz('UTC')->toAtomString() }}</lastmod>
    </sitemap>
</sitemapindex>
