<?php

namespace App\Actions\Tag;

use App\Contracts\Actions\Tag\DetachesTag;
use App\Models\Contracts\TaggableContract;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\Relation;

class DetachTagAction extends TagActionAbstract implements DetachesTag
{
    /**
     * Validate and detach the tag.
     *
     * @param  string  $taggable_type
     * @param  integer  $taggable_id
     * @param  integer  $tag_id
     * @return void
     */
    public function detach(string $taggable_type, int $taggable_id, int $tag_id): void
    {
        $this->tag = $this->resolveTag($tag_id);
        $this->taggable = $this->resolveTaggable($taggable_type, $taggable_id);

        $this->authorize('update', $this->tag);
        $this->authorize('update', $this->taggable);

        $this->taggable->tags()->detach($this->tag);
    }

    /**
     * Get the Tag by its primary key.
     *
     * @param  integer  $id
     * @return Tag
     */
    protected function resolveTag(int $id): Tag
    {
        return Tag::withoutEvents(
            fn () => Tag::withoutGlobalScopes()
                ->withOnly([])
                ->findOrFail($id, ['id'])
        );
    }

    /**
     * Get the model associated with a polymorphic type by its primary key.
     *
     * @param  string  $type
     * @param  integer  $id
     * @return TaggableContract
     */
    protected function resolveTaggable(string $type, int $id): TaggableContract
    {
        $model = Relation::getMorphedModel($type);

        return $model::withoutEvents(
            fn () => $model::withoutGlobalScopes()
                ->withOnly([])
                ->findOrFail($id, ['id'])
        );
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
