<?php

namespace App\Contracts;

interface JsonApiContract
{
    public const API_URL = 'api/'.self::ROUTE_API_VERSION;
    public const HEADER_ACCEPT = 'application/vnd.api+json';
    public const HEADER_CONTENT_TYPE = self::HEADER_ACCEPT;
    public const HEADER_RESOURCE = 'X-JSON-API-RESOURCE';
    public const ROUTE_API_VERSION = 'v1';
    public const RESORCE_TO_MODEL_MAP = [
        \App\Models\Article::TABLE => \App\Models\Article::class,
        \App\Models\Atachment::TABLE => \App\Models\Atachment::class,
        \App\Models\Comment::TABLE => \App\Models\Comment::class,
        \App\Models\Category::TABLE => \App\Models\Category::class,
        \App\Models\Tag::TABLE => \App\Models\Tag::class,
        \App\Models\User::TABLE => \App\Models\User::class,
    ];
    public const SPEC_VERSION = '1.1';

    public function isApiUrl(): bool;

    public function resourceName(): string;

    public function resourceModelName(): string;
}
