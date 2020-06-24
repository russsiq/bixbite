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
        $searchTerm = $this->fullTextWildcards($term);

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
        // https://dev.mysql.com/doc/refman/8.0/en/fulltext-boolean.html
        // $reservedSymbols = ['+', '-', '@', '>', '<', '(', ')', '~', '*', '"', '`', "'"];

        return Str::of($term)
            // Проще оставить только буквы, цифры и пробельные символы.
            ->replaceMatches('/[^[:alnum:][:space:]]/u', '')
            ->explode(' ')
            ->map(function (string $word) {
                return trim($word);
            })
            ->filter(function (string $word) {
                // Исключаем слова короче трех символов.
                return mb_strlen($word, 'utf-8') >= 3;
            })
            ->implode(' ');
    }
}
