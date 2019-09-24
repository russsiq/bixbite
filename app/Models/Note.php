<?php

namespace BBCMS\Models;

use BBCMS\Models\User;
use BBCMS\Models\BaseModel;
use BBCMS\Models\Mutators\NoteMutators;
use BBCMS\Models\Observers\NoteObserver;

use BBCMS\Models\Relations\Fileable;

class Note extends BaseModel
{
    use NoteMutators;
    use Fileable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'image_id' => null,
        'title' => '',
        'slug' => '',
        'description' => '',
        'is_completed' => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'image_id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'is_completed' => 'bool',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'image_id',
        'title',
        'slug',
        'description',
        'is_completed',
    ];

    protected $allowedFilters = [
        'is_completed',
    ];

    protected $orderableColumns = [
        'id',
        'title',
        'created_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(NoteObserver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }
}
