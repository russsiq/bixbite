<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach ($categories as $category)
    <url>
        <loc>{{ $category->url }}</loc>
        @if ($category->updated_at)
            <lastmod>{{ $category->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        @endif
        <changefreq>{{ setting('system.categories_changefreq', 'weekly') }}</changefreq>
        <priority>{{ setting('system.categories_priority', '0.6') }}</priority>
        @if ($category->image)
            <image:image>
                <image:loc>{{ $category->image->url }}</image:loc>
            </image:image>
        @endif
    </url>
    @endforeach
</urlset>
