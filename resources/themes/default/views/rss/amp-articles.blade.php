<?xml version="1.0" encoding="UTF-8" ?>
<rss xmlns:yandex="http://news.yandex.ru"
    xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:turbo="http://turbo.yandex.ru"
    version="2.0">
    <channel>
        <link>{{ route('home') }}</link>
        <title>{{ config('app.name') }}</title>
        <description>{{ setting('system.meta_description') }}</description>
        {{-- <turbo:analytics id="88888888" type="Yandex"></turbo:analytics> --}}
        <language>{{ setting('system.app_locale') }}</language>
        @foreach ($articles as $article)
        <item turbo="true">
            <link>{{ $article->url }}</link>
            <title>{{ $article->title }}</title>
            <pubDate>{{ ($article->updated_at ?? $article->created_at)->tz('UTC')->toRfc822String() }}</pubDate>
            <turbo:content>
                <![CDATA[
                    <header>
                        @if ($image = $article->image)
                        <figure>
                            <img src="{{ $image->url }}" alt="{{ $image->title }}" />
                            <figcaption>{{ $image->title }}</figcaption>
                        </figure>
                        @endif
                        <h1>{{ $article->title }}</h1>
                        {{-- <h2>номер телефона</h2> --}}
                        {{-- <menu>
                            <a href="http://example.com/page1.html">Пункт меню 1</a>
                            <a href="http://example.com/page2.html">Пункт меню 2</a>
                        </menu> --}}
                    </header>
                    {!! strip_tags($article->content, '<p><br><figure><img><iframe><ul><ol><li><abbr><a><b><i><u><sup><sub><small><pre></pre>') !!}
                ]]>
            </turbo:content>
        </item>
        @endforeach
    </channel>
</rss>
