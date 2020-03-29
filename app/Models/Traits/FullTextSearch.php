<?php

namespace App\Models\Traits;

// Сторонние зависимости.
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

/**
 * Source https://arianacosta.com/php/laravel/tutorial-full-text-search-laravel-5/.
 */
trait FullTextSearch
{
    /**
     * Диапазон запроса полнотекстового поиска.
     * @param  Builder  $builder
     * @param  string  $term
     * @return void
     */
    public function scopeSearch(Builder $builder, string $term): void
    {
        $columns = $this->fullTextColumns();
        $searchTerm = $this->fullTextWildcards(html_secure($term));

        $builder->selectRaw(
                "MATCH ({$columns}) AGAINST (? in boolean mode) as REL", [
                $searchTerm
            ])
            ->whereRaw(
                "MATCH ({$columns}) AGAINST (? in boolean mode)", [
                $searchTerm
            ])
            ->orderBy('REL', 'desc');
    }

    /**
     * Подготовить столбцы для полнотекстового поиска.
     * @return string
     */
    protected function fullTextColumns(): string
    {
        $table = $this->getTable();
        $grammar = $this->getQuery()->getGrammar();

        return collect($this->searchable)
            ->map(function (string $column) use ($grammar, $table) {
                return $grammar->wrap($table.'.'.$column);
            })
            ->implode(',');
    }

    /**
     * Подготовить строку для полнотекстового поиска.
     * @param  string  $term
     * @return string
     */
    protected function fullTextWildcards(string $term): string
    {
        // Удаление символов, используемых MySQL.
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];

        return Str::of($term)
            ->replace($reservedSymbols, '')
            ->explode(' ')
            ->filter(function (string $word) {
                // Исключаем слова короче трех символов.
                return strlen(trim($word)) >= 3;
            })
            ->map(function (string $word) {
                return $word; // '+' . $word . '*';
            })
            ->implode(' ');
    }
}
