<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\Article;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->canDo('admin.articles.index');
    }

    public function view(User $user, Article $article)
    {
        return $user->canDo('admin.articles.view');
    }

    public function create(User $user)
    {
        return $user->canDo('admin.articles.create');
    }

    public function update(User $user, Article $article)
    {
        // return $user->id === $article->user_id;
        return $user->canDo('admin.articles.update');
    }

    public function otherUpdate(User $user)
    {
        return $user->canDo('admin.articles.other_update');
    }

    public function delete(User $user, Article $article) {
        return ($user->canDo('admin.articles.delete')  && $user->id == $article->user_id);
    }
}
