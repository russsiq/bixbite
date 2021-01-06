<?php

use App\Http\Controllers\Rss\AmpController;
use App\Http\Controllers\Rss\SitemapController;
use Illuminate\Support\Facades\Route;

// Карты сайта. https://www.sitemaps.org/protocol.html.
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.xml');
Route::get('sitemap-home.xml', [SitemapController::class, 'home'])->name('sitemap-home.xml');
Route::get('sitemap-articles.xml', [SitemapController::class, 'articles'])->name('sitemap-articles.xml');
Route::get('sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap-categories.xml');

// Турбо страницы для Yandex. https://yandex.ru/support/webmaster/turbo/feed.html.
Route::get('amp-articles.xml', AmpController::class)->name('amp-articles.xml');
