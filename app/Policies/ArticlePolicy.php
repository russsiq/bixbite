<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\Article;

class ArticlePolicy extends BasePolicy
{
    public function massUpdate(User $user)
    {
        return $this->update($user);

        return $user->canDo('articles.massUpdate');
    }

    // public function delete(User $user, Article $article) {
    //     return ($user->canDo('articles.delete')  && $user->id == $article->user_id);
    // }
}
