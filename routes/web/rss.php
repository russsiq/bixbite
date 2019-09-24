<?php

// Карты сайта. https://www.sitemaps.org/protocol.html.
Route::get('sitemap.xml', 'SitemapController@index')->name('sitemap.xml');
Route::get('sitemap/home.xml', 'SitemapController@home')->name('sitemap.home.xml');
Route::get('sitemap/articles.xml', 'SitemapController@articles')->name('sitemap.articles.xml');
Route::get('sitemap/categories.xml', 'SitemapController@categories')->name('sitemap.categories.xml');

// Турбо страницы для Yandex. https://yandex.ru/support/webmaster/turbo/feed.html.
Route::get('amp/articles.xml', 'SitemapController@ampArticles')->name('amp.articles.xml');
