<?php

namespace BBCMS\Models\Relations;

use BBCMS\Models\User;

trait hasFollows
{
	public function follows()
	{
		return $this->belongsToMany(User::class, 'follows_users', 'user_id', 'follow_id');
	}

    public function follow(User $user)
	{
		$this->follows()->syncWithoutDetaching($user->id);
	}

	public function unfollow(User $user)
	{
		$this->follows()->detach($user->id);
	}
}
