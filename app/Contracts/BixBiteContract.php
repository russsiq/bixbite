<?php

namespace App\Contracts;

interface BixBiteContract
{
    public function dashboard(): string;
    public function dashboardPath(string $path = ''): string;
    public function dashboardViewsPath(string $path = ''): string;

    public function locale(): string;
    public function fakerLocale(): string;
    public function fallbackLocale(): string;

    public function theme(): string;
    public function themePath(string $path = ''): string;
    public function themeViewsPath(string $path = ''): string;
}
