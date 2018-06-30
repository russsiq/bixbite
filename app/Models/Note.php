<?php

namespace BBCMS\Models;

use BBCMS\Models\{BaseModel, User};

class Note extends BaseModel
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
    protected $casts = [
        'is_completed' => 'bool',
    ];
    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'is_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = string_slug($value);
    }

    public function getUrlAttribute()
    {
        return route('admin.notes.show', [$this]);
    }

    public function getCreatedAttribute()
    {
        return is_null($this->created_at) ? null : $this->asDateTime($this->created_at)->diffForHumans();
    }
}
