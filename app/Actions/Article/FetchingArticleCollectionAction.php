<?php

namespace App\Actions\Article;

use App\Contracts\Actions\Article\FetchingArticleCollection as FetchingArticleCollectionContract;
use App\Http\Resources\V1\ArticleCollection;
use App\Models\Article;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\SoftDeletes;

class FetchingArticleCollectionAction implements FetchingArticleCollectionContract
{
    /** @var EloquentBuilder|QueryBuilder */
    protected $builder;

    /** @var array */
    protected $query = [
        'include' => [],
        'fields' => [],
        'filter' => [],
        'sort' => [],
        'page' => [],
    ];

    public function fetchCollection(array $query): ArticleCollection
    {
        $this->setQuery($query);

        $this->collectFieldsets();

        $this->includeRelatedResource();

        $articles = $this->builder()->get(
            $this->fieldsForType($this->resourceTable())
        );

        return new ArticleCollection($articles);
    }

    public function resourceTable(): string
    {
        return self::MODEL::TABLE;
    }

    protected function setQuery(array $query = []): self
    {
        $this->query = array_merge($this->query, $query);

        return $this;
    }

    protected function collectFieldsets(): self
    {
        // Определить, поля каких типов запрашивает пользователь.
        foreach ($this->query['fields'] as $type => $fields) {
            if ($type !== ($table = $this->normalizeTable($type))) {
                unset($this->query['fields'][$type]);

                $this->query['fields'][$table] = $fields;
            }
        }

        // Собрать поля, указанные в параметре сортировки.
        foreach ($this->query['sort'] as $field) {
            $table = $this->resourceTable();
            $field = trim($field, '-');

            if (false !== strpos($field, '.')) {
                [$table, $field] = array_slice(explode('.', $field), -2);
            }

            $table = $this->normalizeTable($table);

            if (isset($this->query['fields'][$table])) {
                $this->query['fields'][$table][] = $field;
            }
        }

        // Собрать поля, указанные в параметре фильтрации.
        foreach ($this->query['filter'] as $filter) {
            $table = $this->resourceTable();
            $field = $filter['field'];

            if (false !== strpos($field, '.')) {
                [$table, $field] = array_slice(explode('.', $field), -2);
            }

            $table = $this->normalizeTable($table);

            if (isset($this->query['fields'][$table])) {
                $this->query['fields'][$table][] = $field;
            }
        }

        // // Собрать поля, используемые в связанных ресурсах.
        // foreach ($this->query['include'] as $type) {
        //     $table = $this->normalizeTable($type);
        //     // ...
        // }

        // Отфильтруем и оставим только уникальные.
        foreach ($this->query['fields'] as $table => $fields) {
            $this->query['fields'][$table] = array_values(array_unique(array_filter(
                $fields
            )));
        }

        return $this;
    }

    protected function includeRelatedResource(): self
    {
        foreach ($this->query['include'] as $include) {
            $this->setRelationship($this->builder(), $include);
        }

        return $this;
    }

    protected function setRelationship(EloquentBuilder $builder, string $resource): void
    {
        $builder->with($resource, function (Relation $query) {
            $query->addSelect(
                $this->fieldsForTable(
                    $query->getRelated()->getTable()
                )
            );
        });
    }

    protected function fieldsForType(string $type): array
    {
        return $this->fieldsForTable($this->normalizeTable($type));
    }

    protected function fieldsForTable(string $table): array
    {
        return $this->query['fields'][$table] ?? ['*'];
    }

    protected function builder(): EloquentBuilder|QueryBuilder
    {
        return $this->builder ?? $this->builder = Article::query();
    }

    protected function normalizeTable(string $table): string
    {
        return Str::snake(Str::pluralStudly($table));
    }
}
