<?php

namespace App\Actions\Tag;

use App\Contracts\Actions\Tag\FetchesTag;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class FetchTagAction extends TagActionAbstract implements FetchesTag
{
    /**
     * Validate query parameters and return a specified tag.
     *
     * @param  mixed  $field
     * @param  array  $input
     * @return Tag
     */
    public function fetch(mixed $field, array $input): Tag
    {
        $this->tag = Tag::tap(
            fn ($query) => $query->where($query->getModel()->getRouteKeyName(), $field)->firstOrFail()
        );

        $this->authorize('view', $this->tag);

        $this->tag->loadCount([
            'articles',
        ]);

        return $this->tag;
    }

    /**
     * Validate query parameters and return a collection of tags.
     *
     * @param  array  $input
     * @return EloquentCollection|Paginator
     */
    public function fetchCollection(array $input): EloquentCollection|Paginator
    {
        $this->authorize('viewAny', Tag::class);

        $query = Tag::query()
            ->select([
                'id',
                'title',
                'slug',
            ])
            ->withCount([
                'articles',
            ]);

        if (! empty($input['title'])) {
            return $query->searchByKeyword($input['title'])
                ->orderBy('articles_count')
                ->get();
        }

        return $query->orderBy('title')
            ->paginate();
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            //
        ];
    }
}
