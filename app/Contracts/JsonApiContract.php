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
        'articles' => \App\Models\Article::class,
        'atachments' => \App\Models\Atachment::class,
        'comments' => \App\Models\Comment::class,
        'categories' => \App\Models\Category::class,
        'tags' => \App\Models\Tag::class,
        'users' => \App\Models\User::class,
    ];
    public const SPEC_VERSION = '1.1';
}
