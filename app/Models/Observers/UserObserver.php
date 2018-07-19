<?php

// retrieved, creating, created, updating, updated, saving, saved, deleting,  deleted, restoring, restored
// $original = $user->getOriginal();
// $dirty = $user->getDirty();

namespace BBCMS\Models\Observers;

use BBCMS\Models\User;
use Illuminate\Http\UploadedFile;

class UserObserver
{
    public function retrieved(User $user)
    {
        $user->fillable(array_merge(
            $user->getFillable(),
            $user->x_fields->pluck('name')->toArray()
        ));
    }

    public function saving(User $user)
    {
        //  dd($user);
    }

    public function updating(User $user)
    {
        $dirty = $user->getDirty();

        // Set new or delete old user avatar.
        if (array_key_exists('avatar', $dirty)) {
            // First, we get avatar.
            $avatar = $dirty['avatar'] ?? null;
            // Deleting always.
            $this->deleteAvatar($user);
            // Attaching.
            if ($avatar instanceof UploadedFile and $avatar->isValid()) {
                $this->attachAvatar($user, $avatar);
            }
        }
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
        $this->deleteAvatar($user);
    }

    protected function attachAvatar(User $user, UploadedFile $avatar)
    {
        // Path to folder avatars for store.
        $path = setting('users.avatar_path', 'uploads'.DS.'avatars');

        // Formation of avatar file name.
        [$width, $height] = getimagesize($avatar->getRealPath());
        $extension = $avatar->getClientOriginalExtension();
        $filename = $user->id.'_'.$width.'x'.$height.'.'.$extension;

        // Storage file.
        $avatar->storeAs($path, $filename);

        // Atach to model.
        $user->avatar = $filename;
    }

    protected function deleteAvatar(User $user)
    {
        // Path to folder avatars for store.
        $path = app()->basePath(
            setting('users.avatar_path', 'uploads'.DS.'avatars').DS
        );

        // Get old user avatar file name.
        $avatar = $user->getOriginal('avatar') ?? null;

        // Check and delete, if user already has an avatar.
        if ($avatar and is_file($path.$avatar)) {
            unlink($path.$avatar);
        }
        // ???.
        $user->avatar = null;
    }
}
