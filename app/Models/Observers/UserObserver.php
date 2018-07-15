<?php

// retrieved, creating, created, updating, updated, saving, saved, deleting,  deleted, restoring, restored

namespace BBCMS\Models\Observers;

use BBCMS\Models\User;

class UserObserver
{
    public function creating(User $user)
    {

    }

    public function updating(User $user)
    {
        // dd($user);
    }

    public function deleting(User $user)
    {
        // // 1 Roles. Not used in this version App.
        // $user->roles()->detach();

        // 2 Чтобы вызвать метод `Article::deleting`,
        //   в котором прописаны дополнительные удаляшки.
        $user->articles()->get(['id'])->each->delete();

        // 3 Обновим комментарии якобы они от незарегистрированного пользователя,
        //   но только после удаления новостей пользователя см. пункт 2,
        //   т.к. он мог наоставлять комментов к своим записям, а это лишние запросы.
        $user->comments()->update(['user_id' => null, 'name' => $user->name, 'email' => $user->email]);

        // 4 Просто удаляем, т.к. заметки не имеют реляц. связей.
        $user->notes()->delete();

        // 5 Всегда удаляем аватар пользователя.
        $user->deleteAvatar(true);
    }
}
