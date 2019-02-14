<?php

/**
 * Source https://arianacosta.com/php/laravel/tutorial-full-text-search-laravel-5/.
 */

namespace BBCMS\Models\Traits;

trait FullTextSearch
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                // $words[$key] = '+' . $word . '*';
                $words[$key] = $word.'*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        $columns = implode(',', $this->searchable);

        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

        // $term = $this->fullTextWildcards($term);
        //
        // $query
        //     ->selectRaw('MATCH (title, content) AGAINST (? in boolean mode) as REL', [$term])
        //     ->whereRaw('MATCH (title, content) AGAINST (? in boolean mode)' , $term)
        //     ->orderBy('REL', 'desc')
        //     ;

        return $query;
    }
}
