<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        @if ($lastmod)
            <lastmod>{{ $lastmod->tz('UTC')->toAtomString() }}</lastmod>
        @endif
        <changefreq>{{ setting('system.home_changefreq', 'daily') }}</changefreq>
        <priority>{{ setting('system.home_priority', '0.9') }}</priority>
    </url>
</urlset>
