<?php

namespace App\Models\Observers;

// Сторонние зависимости.
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\UploadedFile;

/**
 * Наблюдатель модели `User`.
 */
class UserObserver extends BaseObserver
{
    /**
     * Обработать событие `retrieved` модели.
     * @param  User  $user
     * @return void
     */
    public function retrieved(User $user): void
    {
        $user->fillable(array_merge(
            $user->getFillable(),
            $user->x_fields->pluck('name')->toArray()
        ));
    }

    /**
     * Обработать событие `updating` модели.
     * @param  User  $user
     * @return void
     */
    public function updating(User $user): void
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

        if (array_key_exists('email', $dirty)
            && $user instanceof MustVerifyEmail
        ) {
            $user->email_verified_at = null;
        }
    }

    /**
     * Обработать событие `saved` модели.
     * @param  User  $user
     * @return void
     */
    public function saved(User $user): void
    {
        $dirty = $user->getDirty();

        if (array_key_exists('email', $dirty)
            && $user instanceof MustVerifyEmail
            && ! $user->hasVerifiedEmail()
        ) {
            $user->sendEmailVerificationNotification();
        }
    }

    /**
     * Обработать событие `deleting` модели.
     * @param  User  $user
     * @return void
     */
    public function deleting(User $user): void
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

    /**
     * Прикрепить аватар к указанному пользователю..
     * @param  User  $user
     * @param  UploadedFile  $avatar
     * @return void
     */
    protected function attachAvatar(User $user, UploadedFile $avatar): void
    {
        // Path to folder avatars for store.
        $path = setting('users.avatar_path', 'public/avatars');

        // Formation of avatar file name.
        [$width, $height] = getimagesize($avatar->getRealPath());
        $extension = $avatar->getClientOriginalExtension();
        $filename = $user->id.'_'.$width.'x'.$height.'.'.$extension;

        // Storage file.
        $avatar->storeAs($path, $filename);

        // Atach to model.
        $user->avatar = $filename;
    }

    /**
     * Открепить и удалить аватар от указанного пользователя.
     * @param  User  $user
     * @return void
     */
    protected function deleteAvatar(User $user): void
    {
        // Get old user avatar file name.
        $avatar = $user->getOriginal('avatar') ?? null;

        if ($avatar) {
            // Path to folder avatars for store.
            $path = base_path(
                setting('users.avatar_path', 'public/avatars')
            );

            // Check and delete, if user already has an avatar.
            if (is_file($filename = $path.'/'.$avatar)) {
                unlink($filename);
            }
        }

        // ???.
        $user->avatar = null;
    }
}
